<?php

namespace Domains\Links\Controllers;

use App\Http\Controllers\Controller;
use Domains\Links\Models\Link;
use Domains\Links\Requests\LinkStoreRequest;
use Illuminate\Http\Response;

class LinksStoreController extends Controller
{
    private Link $links;

    public function __construct(Link $links)
    {
        $this->links = $links;
    }

    public function __invoke(LinkStoreRequest $request): Response
    {
        $link = $this->links->create([
            'link' => $request->input('link'),
            'description' => $request->input('description'),
            'author_name' => $request->input('author_name'),
            'author_email' => $request->input('author_email'),
            'cover_image' => $request->file('cover_image')->getClientOriginalName(),
        ]);

        $link->tags()->attach($request->input('tags.*.id'));

        return Response::create('', Response::HTTP_NO_CONTENT);
    }
}
