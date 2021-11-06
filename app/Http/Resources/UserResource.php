<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
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
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'role'            => $this->role,
            'national_number' => $this->national_number,
            'bank_account'    => $this->bank_account,
            'avatar'          => url('storage/' . $this->avatar),
            'playgrounds'     => $this->whenLoaded('playgrounds', PlayGroundResource::collection($this->playgrounds)),
            'created_at'      => $this->created_at
        ];
    }
}
