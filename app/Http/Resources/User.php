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
        // Get single role
        $role = $this->roles->first();

        return [
            'id'            => $this->id,
            'email'         => $this->email,
            'fullname'      => $this->fullname,
            'gender'        => $this->gender,
            'role'          => $role ? $role->name : null,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at
        ];
    }
}
