<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Question;
use Domains\Discussions\Resources\QuestionResource;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionsIndexController extends Controller
{
    protected Question $question;

    public function __construct(Auth $auth, Question $question)
    {
        if ($auth->guard()->guest()) {
            $this->middleware('throttle:30,1');
        }

        $this->question = $question;
    }

    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $this->validate($request, [
            'author' => ['sometimes', 'integer'],
            'title' => ['sometimes', 'string'],
            'created' => ['sometimes', 'array', 'size:2'],
            'created.from' => ['required_with:created', 'date'],
            'created.to' => ['required_with:created', 'date', 'afterOrEqual:created.from'],
            'resolved' => ['sometimes', 'boolean'],
        ]);

        $question = $this->question
            ->when($request->input('author'),
                fn(Builder $query, int $authorId) => $query->findByAuthorId($authorId))
            ->when($request->input('title'),
                fn(Builder $query, string $title) => $query->findByTitle($title))
            ->when($request->input('created'),
                fn(Builder $query, array $created) => $query->findByCreatedDate([$created['from'], $created['to']]))
            ->when($request->boolean('resolved'),
                fn(Builder $query) => $query->resolved())
            ->when(!$request->boolean('resolved') && $request->input('resolved') != null,
                fn(Builder $query) => $query->nonResolved())
            ->latest()
            ->simplePaginate(15);

        return QuestionResource::collection($question);
    }
}
