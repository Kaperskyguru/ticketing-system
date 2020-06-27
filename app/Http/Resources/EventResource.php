<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'title' => (string) $this->title,
            'description' => (string) $this->description,
            'ticket_price' => (float) $this->ticket_price,
            'event_date' => (string) $this->date
        ];
    }
}
