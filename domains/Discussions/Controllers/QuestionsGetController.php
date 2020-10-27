<?php

namespace Domains\Discussions\Controllers;

use App\Http\Controllers\Controller;
use Domains\Discussions\Models\Question;
use Domains\Discussions\Resources\QuestionResource;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionsGetController extends Controller
{
    protected $query;

    public function __construct(Auth $auth, Question $question)
    {
        if ($auth->guard()->guest()) {
            $this->middleware('throttle:30,1');
        }

        $this->query = $question::query();
    }

    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $this->validate($request, [
            'author' => 'sometimes|integer',
            'title' => 'sometimes|string',
            'created' => 'sometimes|array|size:2',
            'created.from' => 'required_with:created|date',
            'created.to' => 'required_with:created|date|afterOrEqual:created.from',
            'resolved' => 'sometimes|boolean'
        ]);

        $author = $request->get('author');
        if ($author != '') {
            $this->query->where('author_id', $author);
        }

        $title = strtoupper($request->get('title'));
        if ($title != '') {
            $this->query->where('title', 'like', '%'.$title.'%');
        }

        $created = $request->get('created');
        if ($created != null) {
            $this->query->whereBetween('created_at', [$created['from'], $created['to']]);
        }

        $resolved = $request->get('resolved');
        if ($resolved == true) {
            $this->query->whereNotNull('resolved_at');
        } elseif ($resolved == false) {
            $this->query->whereNull('resolved_at');
        }

        return QuestionResource::collection($this->query->paginate(15));
    }
}
