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
            'question_id' => $this->question_id,
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'question' => QuestionResource::collection(
                $this->whenLoaded('question')
            ),
            'author' => UserResource::collection(
                $this->whenLoaded('author')
            ),
        ];
    }
}
