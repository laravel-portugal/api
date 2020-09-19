<?php

namespace Domains\Links\Resources;

use Domains\Tags\Resources\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'link' => $this->link,
            'title' => $this->title,
            'description' => $this->description,
            'author_name' => $this->author_name,
            'author_email' => $this->author_email,
            'cover_image' => $this->cover_image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'approved_at' => $this->approved_at,
            'tags' => TagResource::collection(
                $this->whenLoaded('tags')
            ),
        ];
    }
}
