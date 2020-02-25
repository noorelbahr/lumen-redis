<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NewsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($page) {
                return [
                    'id'            => $page->id,
                    'slug'          => $page->slug,
                    'title'         => $page->title,
                    'content'       => $page->content,
                    'heading_image' => $page->heading_image ? url('storage/news/' . $page->heading_image) : null,
                    'tags'          => explode(',', $page->tags),
                    'created_at'    => $page->created_at,
                    'updated_at'    => $page->updated_at,
                    'comment_count' => $page->comments->count(),
                    'like_count'    => $page->likes->count()
                ];
            })
        ];
    }
}
