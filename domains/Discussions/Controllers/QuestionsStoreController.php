<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class QuestionsStoreController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        (new Question())
            ->forceFill([
                'author_id' => Auth::id(),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
            ])
            ->save();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
