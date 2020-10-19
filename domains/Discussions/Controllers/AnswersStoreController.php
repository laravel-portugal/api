<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Answer;
use Domains\Discussions\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnswersStoreController extends Controller
{
    private Answer $answer;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    public function __invoke(Request $request): Response
    {
        $this->validate($request, [
            'content' => ['required', 'string'],
        ]);

        $question = Question::findOrFail($request[0]);

        $this->answer
            ->forceFill([
                'author_id' => $request->user()->id,
                'question_id' => $question->id,
                'content' => $request->input('content'),
            ])
            ->save();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
