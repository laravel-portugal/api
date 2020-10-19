<?php

namespace Domains\Discussions\Models;

use Domains\Accounts\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use SoftDeletes;

    protected $table = 'question_answers';

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withTrashed();
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class)
            ->withTrashed();
    }
}
