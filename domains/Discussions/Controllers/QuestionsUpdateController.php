<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuestionsUpdateController extends Controller
{
    private Question $questions;

    public function __construct(Question $questions)
    {
        $this->questions = $questions;
    }

    public function __invoke(Request $request, int $questionId): Response
    {
        $question = $this->questions->findOrFail($questionId);

        $this->authorize('update', $question);

        $this->validate($request, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $question->update([
            'title' => $request->input('title'),
            'description' => $request->input('description', $question->description),
        ]);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
