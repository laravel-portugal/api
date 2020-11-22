<?php

namespace Domains\Links\Controllers;

use App\Http\Controllers\Controller;
use Domains\Links\Exceptions\UnapprovedLinkLimitReachedException;
use Domains\Links\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

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
            'author_email' => ['required', 'email', $request->user() ? null : 'unique:users,email'],
            'cover_image' => ['required', 'image'],
            'tags' => ['required', 'array'],
            'tags.*.id' => ['required', 'integer', 'exists:tags'],
        ]);

        throw_unless(
            Gate::allows('create', [Link::class, $request->input('author_email')]),
            new UnapprovedLinkLimitReachedException()
        );

        $link = $this->links->create([
            'link' => $request->input('link'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'author_name' => optional($request->user())->name ?? $request->input('author_name'),
            'author_email' => optional($request->user())->email ?? $request->input('author_email'),
            'cover_image' => $request->file('cover_image')->store('cover_images', 'public'),
        ]);

        $link->tags()->attach($request->input('tags.*.id'));

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
