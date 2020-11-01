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
    private Question $question;

    public function __construct(Answer $answer, Question $question)
    {
        $this->answer   = $answer;
        $this->question = $question;
    }

    public function __invoke(Request $request, int $questionId): Response
    {
        $this->validate($request, [
            'content' => ['required', 'string'],
        ]);

        $question = $this->question->findOrFail($questionId);

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
