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

    public function __invoke(int $questionId, Request $request): Response
    {
        $question = $this->questions->newModelQuery()->findOrFail($questionId);

        if ($request->user()->cannot('update', $question)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate($request, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $question->update([
            'title' => $request->input('title'),
            'description' => $request->input('description') ?? $question->description,
        ]);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
