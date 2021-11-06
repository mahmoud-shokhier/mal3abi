<?php

namespace App\Http\Resources;

use App\PlaygroundImage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PlaygroundImage */
class PlaygroundImageResource extends JsonResource
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
            'image'      => url($this->image),
            'created_at' => $this->created_at
        ];
    }
}
