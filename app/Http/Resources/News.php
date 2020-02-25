<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class News extends Resource
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
            'title'         => $this->title,
            'content'       => $this->content,
            'heading_image' => $this->heading_image ? url('storage/news/' . $this->heading_image) : null,
            'tags'          => explode(',', $this->tags),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'comments'      => new NewsCommentCollection($this->comments),
            'likes'         => new NewsLikeCollection($this->likes),
        ];
    }
}
