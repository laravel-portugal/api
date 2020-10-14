<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class QuestionsUpdateController extends Controller
{
    public function __invoke(int $questionId, Request $request): Response
    {
        $question = Question::findOrFail($questionId);

        if (Auth::user()->cannot('update', $question)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate($request, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $question->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
