<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Question;
use Domains\Discussions\Resources\QuestionResource;
use GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware;
use Illuminate\Contracts\Auth\Factory as Auth;

class QuestionsViewController extends Controller
{
    private Question $question;

    public function __construct(Auth $auth, Question $question)
    {
        $this->question = $question;

        if ($auth->guard()->guest()) {
            $this->middleware(ThrottleMiddleware::class, ['30','1']);
        }
    }

    public function __invoke(int $questionId)
    {
        return QuestionResource::make($this->question->findOrFail($questionId));
    }
}
