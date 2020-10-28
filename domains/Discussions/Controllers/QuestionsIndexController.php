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
            'author' => 'sometimes|integer',
            'title' => 'sometimes|string',
            'created' => 'sometimes|array|size:2',
            'created.from' => 'required_with:created|date',
            'created.to' => 'required_with:created|date|afterOrEqual:created.from',
            'resolved' => 'sometimes|boolean',
        ]);

        $question = $this->question
            ->when($authorId = $request->get('author'),
                fn(Builder $query, int $authorId) => $query->where('author_id', $authorId))
            ->when($request->get('title'),
                fn(Builder $query, string $title) => $query->where('title', 'like', '%'.strtoupper($title).'%'))
            ->when($request->get('created'),
                fn(Builder $query, array $created) => $query->whereBetween('created_at', [$created['from'], $created['to']]))
            ->when($request->get('resolved'),
                fn(Builder $query, bool $resolved) => $query->whereNotNull('resolved_at'))
            ->when(!$request->get('resolved'),
                fn(Builder $query, bool $resolved) => $query->whereNull('resolved_at'))
            ->simplePaginate(15);

        return QuestionResource::collection($question);
    }
}
