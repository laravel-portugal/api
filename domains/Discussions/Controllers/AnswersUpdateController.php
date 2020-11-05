<?php


namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Answer;
use Domains\Discussions\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnswersUpdateController extends Controller
{
    private Answer $answer;

    public function __construct(Question $question, Answer $answer)
    {
        $this->question = $question;
        $this->answer = $answer;
    }

    public function __invoke(int $questionId, int $answerId, Request $request): Response
    {
        $answer = $this->answer->where('question_id', $questionId)->findOrFail($answerId);

        $this->authorize('update', $answer);

        $this->validate($request, [
            'content' => ['required', 'string'],
        ]);

        $answer->update([
            'content' => $request->input('content'),
        ]);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
