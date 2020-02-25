<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Role extends Resource
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
            'id'            => $this->id,
            'slug'          => $this->slug,
            'name'          => $this->name,
            'permissions'   => $this->mapped_permissions,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at
        ];
    }
}
