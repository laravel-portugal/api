<?php

namespace Domains\Discussions\Resources;

use Domains\Accounts\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'question' => QuestionResource::make($this->question),
            'author' => UserResource::make($this->author),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
