<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreEvent;
use App\Http\Requests\StoreTicket;
use App\Http\Requests\StoreUserEvent;
use App\Http\Requests\UpdateEvent;
use App\Http\Resources\EventResource;
use App\Notifications\TicketNotification;
use App\Ticket;
use App\User;
use App\UserEvent;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Keygen\Keygen;

class EventController extends Controller
{
    private $duration = 60;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->has('page') ? $request->query('page') : 1;
        $size = $request->has('size') ? $request->query('size') : 10;

        $events = Cache::remember('events_page_' . $page.'_size_'.$size, $this->duration, function () use ($size) {
            $data = Event::latest()->paginate($size);
            if ($data->items()) {
                return $data;
            }
            return null;
        });
        if ($events) {
            Log::info('All event retrieved and cached');
            return EventResource::collection($events);
        }

        Log::error('Events not found');
        return $this->response('Events not found', 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEvent $request)
    {
        if (request()->user()->tokenCan('can-add')) {
            $event = Event::create($request->validated());

            if ($event) {
                Cache::put('event_id_' . $event->id, $event, $this->duration);
                Log::info('New Event with id: ' . $event->id . 'created and cached');
                return new EventResource($event);
            }

            Log::debug('Event was not created, something wrong with server');
            return $this->response('Internal Server Error', 500);
        }
        throw new UnauthorizedException();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cachedEvent = $this->findEvent($id);
        return new EventResource($cachedEvent);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEvent $request, $id)
    {
        if (request()->user()->tokenCan('can-edit')) {
            $cachedEvent = $this->findEvent($id);

            if ($cachedEvent->update($request->all())) {
                if (Cache::has('event_id_' . $id)) {
                    Cache::forget('event_id_' . $id);
                }

                $cachedEvent = $this->findEvent($id);
                return new EventResource($cachedEvent);
            }
        }
        throw new UnauthorizedException();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->user()->tokenCan('can-delete')) {
            $cachedEvent = $this->findEvent($id);

            if (!$cachedEvent && $cachedEvent->delete()) {
                Log::info('Deleted event with id: ' . $id);

                if (Cache::has('event_id_' . $id)) {
                    Cache::forget('event_id_' . $id);
                }
                return response()->json([
                    'message' => 'Event deleted successfully',
                ], 204);
            }

            Log::debug('Could not delete event with id: ' . $id . ', Something wrong with server');
            return $this->response('Event could not be deleted, Internal Server Error', 500);
        }
        throw new UnauthorizedException();
    }

    public function buy(StoreTicket $request, $id)
    {
        // Find Event
        $event = $this->findEvent($id);

        // Check if price matches
        if ($event->ticket_price != $request->amount) {
            $message = 'Ticket with id: '.$event->id.' amount: '.$event->ticket_price.' does not equal to User amount: '.$request->amount;
            Log::debug($message);
            return $this->response($message, 422);
        }

        $ticket = new Ticket();
        $ticket->user_id = $request->user_id;
        $ticket->event_id = $event->id;
        $ticket->amount = $request->amount;
        $ticket->code = Keygen::numeric(5)->prefix(mt_rand(1, 9))->generate(true);

        if ($ticket->save()) {
            Log::info('User with id: ' . $request->user_id . ' purchase ticket with id: ' . $ticket->id . ' for event with id: ' . $event->id);

            // Send User Email, Send Code
            $user = User::find($request->user_id);
            $user->notifyNow(new TicketNotification($ticket, $event));
            return $this->response('Payment for event with id: ' . $event->id . ' was successful', 200);
        }

        Log::debug('User with id: '.$request->user_id.' could not pay for ticket with id: '.$id.', Something wrong with server');
        return $this->response('Internal Server Error, Please try again', 500);
    }

    public function join(StoreUserEvent $request, $id)
    {

        // Check if user already join event
        $user = $request->user_id;
        $ticket = Ticket::where('user_id', $user)->where('code', $request->code)->where('event_id', $id)->first();

        if (!$ticket) {
            // Throw Ticket not found exception
            Log::error('User with id: ' . $user . 'tried to used invalid ticket code: ' . $request->code . ' for event with id: ' . $id);
            return $this->response('Ticket code not valid', 422);
        }

        if ($ticket && $ticket->is_used && $ticket->date_used <= now()) {
            // throw Used_ticket_Error
            Log::error('User with id: ' . $user . 'tried to used already used ticket code: ' . $request->code . ' for event with id: ' . $id);
            return $this->response('Ticket already used', 422);
        }

        $joinEvent = new UserEvent;
        $joinEvent->user_id = $user;
        $joinEvent->event_id = $id;

        $ticket->is_used = true;
        $ticket->used_date = now();

        if ($joinEvent->save() && $ticket->save()) {
            // Send Success Response
            Log::info('User with id: ' . $user . 'successfully Joined Event with id: ' . $id);
            return $this->response('You\'ve joined event with id: ' . $id . ' successfully', 200);
        }

        Log::debug('Could not Join Event, something wrong with server');
        return $this->response('Internal Server Error, Please try again', 500);
    }

    private function findEvent($id)
    {
        $cachedEvent = Cache::remember(
            'event_id_' . $id,
            $this->duration,
            function () use ($id) {
                return Event::find($id);
            }
        );

        if ($cachedEvent) {
            Log::info('Single event with id: ' . $cachedEvent->id . 'retrieved and cached');
            return $cachedEvent;
        }

        Log::debug('Event was not created, something wrong with server');
        return $this->response('Event not found', 404);
    }
}
