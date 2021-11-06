<?php

namespace App\Http\Resources;

use App\Playground;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Playground
 * @property mixed distance
 */
class PlayGroundResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'address'      => $this->address,
            'lat'          => $this->lat,
            'long'         => $this->long,
            'price_day'    => $this->price_day,
            'price_night'  => $this->price_night,
            'day_time'     => $this->day_time,
            'night_time'   => $this->night_time,
            'status'       => $this->status,
            'images'       => PlaygroundImageResource::collection($this->whenLoaded('images')),
            'reservations' => ReservationResource::collection($this->whenLoaded('reservations')),
            'rate'         => $this->rate,
            'distance'     => $this->distance,
            'created_at'   => $this->created_at
        ];
    }
}
