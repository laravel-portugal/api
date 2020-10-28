<?php

namespace Domains\Discussions\Resources;

use Domains\Accounts\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'author' => UserResource::make($this->author),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'resolved_at' => $this->resolved_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
