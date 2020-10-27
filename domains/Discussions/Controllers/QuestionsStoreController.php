<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuestionsStoreController extends Controller
{
    private Question $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function __invoke(Request $request): Response
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $this->question
            ->forceFill([
                'author_id' => $request->user()->id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
            ])
            ->save();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
