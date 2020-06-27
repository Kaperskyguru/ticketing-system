<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Http\Requests\StoreEvent;
use App\Http\Requests\StoreTicket;
use App\Http\Requests\StoreUserEvent;
use App\Http\Resources\EventResource;
use App\Ticket;
use App\UserEvent;
use Illuminate\Support\Facades\Cache;
use Keygen\Keygen;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $events = Cache::remember('events_page_' . $page, $this->duration, function () use ($size) {
            $data = Event::with('')->latest()->paginate($size);
            if ($data->items())
                return $data;
            return null;
        });
        if ($events) {
            return EventResource::collection($events);
        }
        return response()->json([
            'message' => 'Events not found'
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEvent $request)
    {
        $event = Event::create($request->validated());

        if ($event) {
            Cache::put('event' . '_id_' . $event->id, $event, $this->duration);
            return new EventResource($event);
        }
        return response()->json([
            'message' => 'Event not created',
        ], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cachedEvent = Cache::remember('event_id_' . $id, $this->duration, function () use ($id) {
            return Event::with('tickets')->find($id);
        });

        if ($cachedEvent) {
            return new EventResource($cachedEvent);
        }
        return response()->json([
            'message' => 'Event not found',
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cachedEvent = Cache::remember('event' . '_id_' . $id, $this->duration, function () use ($id) {
            return Event::find($id);
        });

        if (!$cachedEvent) {
            throw new NotFoundHttpException();
        }

        if ($cachedEvent->update($request->all())) {
            if (Cache::has('event' . '_id_' . $id)) {
                Cache::forget('event' . '_id_' . $id);
            }

            $cachedEvent = Cache::put('event' . '_id_' . $cachedEvent->id, $cachedEvent, $this->duration);

            return new EventResource($cachedEvent);
        }
        return response()->json([
            'message' => 'Event not found',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cachedEvent = Cache::remember('event' . '_id_' . $id, $this->duration, function () use ($id) {
            return Event::find($id);
        });

        if (!$cachedEvent && $cachedEvent->delete()) {
            if (Cache::has('event' . '_id_' . $id)) {
                Cache::forget('event' . '_id_' . $id);
            }
            return response()->json([
                'message' => 'Event deleted successfully',
            ], 200);
        }

        return response()->json([
            'message' => 'Event not found',
        ], 404);
    }

    public function buy(StoreTicket $request, $user)
    {

        $ticket = new Ticket();
        $ticket->user_id = $user;
        $ticket->event_id = $request->event_id;
        $ticket->amount = $request->amount;
        $ticket->code = Keygen::numeric(5)->prefix(mt_rand(1, 9))->generate(true);;

        if ($ticket->save()) {
            // Send User Email, Send Code

            return response()->json([
                'message' => 'Payment for event with id ' . $request->event_id . ' was successful',
            ], 200);
        }

        return response()->json([
            'message' => 'Internal Server Error, Please try again',
        ], 500);
    }

    public function join(StoreUserEvent $request, $user)
    {

        // Check if user already join event
        $event = $request->event_id;
        $ticket = Ticket::where('user_id', $user)->where('code', $request->code)->where('event_id', $event)->first();

        if (!$ticket) {
            // Throw Ticket not found exception
            return response()->json([
                'message' => 'Ticket code not valid',
            ], 404);
        }

        if ($ticket->is_used && $ticket->date_used->isPast()) {
            // throw Used_ticket_Error
            return response()->json([
                'message' => 'Ticket already used',
            ], 404);
        }

        $joinEvent = new UserEvent;
        $joinEvent->user_id = $user;
        $joinEvent->event_id = $event;

        if ($joinEvent->save()) {
            // Send Success Email
        }

        return response()->json([
            'message' => 'Internal Server Error, Please try again',
        ], 500);
    }
}
