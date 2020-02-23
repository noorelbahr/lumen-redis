<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
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
            'id' => $this->id,
            'email' => $this->email,
            'fullname' => $this->fullname,
            'gender' => $this->gender,
//            'picture' => $this->picture ? url($this->picture) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
