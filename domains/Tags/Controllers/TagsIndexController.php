<?php

namespace Domains\Tags\Controllers;

use App\Http\Controllers\Controller;
use Domains\Tags\Models\Tag;
use Domains\Tags\Resources\TagResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagsIndexController extends Controller
{
    public function __invoke(Tag $tags): AnonymousResourceCollection
    {
        return TagResource::collection($tags->all());
    }
}
