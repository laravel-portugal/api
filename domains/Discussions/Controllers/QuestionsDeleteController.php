<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuestionsDeleteController extends Controller
{
    private Question $questions;

    public function __construct(Question $questions)
    {
        $this->questions = $questions;
    }

    public function __invoke(Request $request, int $questionId): Response
    {
        $question = $this->questions->findOrFail($questionId);

        $this->authorize('delete', $question);

        $question->delete();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
