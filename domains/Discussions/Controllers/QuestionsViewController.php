<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Question;
use Domains\Discussions\Resources\QuestionResource;
use Illuminate\Contracts\Auth\Factory as Auth;

class QuestionsViewController extends Controller
{
    private Question $question;

    public function __construct(Auth $auth, Question $question)
    {
        $this->question = $question;

        if ($auth->guard()->guest()) {
            $this->middleware('throttle:30,1');
        }
    }

    public function __invoke(int $questionId): QuestionResource
    {
        return QuestionResource::make($this->question->findOrFail($questionId));
    }
}
