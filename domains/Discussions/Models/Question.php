<?php

namespace Domains\Discussions\Models;

use Domains\Accounts\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withTrashed();
    }
}
