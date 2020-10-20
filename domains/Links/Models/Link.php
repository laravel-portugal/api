<?php

namespace Domains\Links\Models;

use Domains\Tags\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use SoftDeletes;

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected $fillable = [
        'link',
        'title',
        'description',
        'author_name',
        'author_email',
        'cover_image',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereNotNull('approved_at');
    }

    public function scopeUnapproved(Builder $query): Builder
    {
        return $query->whereNull('approved_at');
    }

    public function scopeForAuthorWithEmail(Builder $query, string $email): Builder
    {
        return $query->where('author_email', $email);
    }
}
