<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Question;
use Domains\Discussions\Resources\QuestionResource;

class QuestionsViewController extends Controller
{
    private Question $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function __invoke(int $questionId)
    {
        $question = $this->question->findOrFail($questionId);
        return QuestionResource::make($question);
    }
}