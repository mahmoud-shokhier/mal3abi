<?php

namespace App\Http\Resources;

use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Reservation */
class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'day'        => $this->day,
            'start'      => $this->start,
            'end'        => $this->end,
            'status'     => $this->status,
            'notes'      => $this->notes,
            'user'       => UserResource::make($this->whenLoaded('user')),
            'playground' => PlayGroundResource::make($this->whenLoaded('playground')),
            'created_at' => $this->created_at
        ];
    }
}
