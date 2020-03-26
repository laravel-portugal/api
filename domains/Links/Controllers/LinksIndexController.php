<?php

namespace Domains\Links\Controllers;

use App\Http\Controllers\Controller;
use Domains\Links\Models\Link;
use Domains\Links\Resources\LinkResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LinksIndexController extends Controller
{
    private Link $links;

    public function __construct(Link $links)
    {
        $this->links = $links;
    }

    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $links = $this->links
            ->when($request->input('include'), static function (Builder $query, string $includes) {
                return $query->with(
                    explode(',', $includes)
                );
            })
            ->approved()
            ->simplePaginate();

        return LinkResource::collection($links);
    }
}
