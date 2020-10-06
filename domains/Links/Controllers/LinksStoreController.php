<?php

namespace Domains\Links\Controllers;

use App\Http\Controllers\Controller;
use Domains\Links\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LinksStoreController extends Controller
{
    private Link $links;

    public function __construct(Link $links)
    {
        $this->links = $links;
    }

    public function __invoke(Request $request): Response
    {
        $this->validate($request, [
            'link' => ['required', 'string', 'url'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'author_name' => ['required', 'string'],
            'author_email' => ['required', 'email'],
            'cover_image' => ['required', 'image'],
            'tags' => ['required', 'array'],
            'tags.*.id' => ['required', 'integer', 'exists:tags'],
        ]);

        $link = $this->links->create([
            'link' => $request->input('link'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'author_name' => $request->input('author_name'),
            'author_email' => $request->input('author_email'),
            'cover_image' => $request->file('cover_image')->store('cover_images'),
        ]);

        $link->tags()->attach($request->input('tags.*.id'));

        return Response::create('', Response::HTTP_NO_CONTENT);
    }
}
