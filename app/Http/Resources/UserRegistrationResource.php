<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'full_name'=>$this->full_name,
            'email'=>$this->email,
            'address'=>$this->address,
            'social_media_link'=>$this->social_media_link,
            'phone_number'=>$this->phone_number,
            'role'=>$this->role,
            'status'=>$this->status

        ];
    }

}
