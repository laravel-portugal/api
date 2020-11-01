<?php

namespace Domains\Discussions\Models;

use Domains\Accounts\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description'];

    protected $dates = ['resolved_at'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withTrashed();
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
  
    public function scopeFindByAuthorId(Builder $query, int $term): Builder
    {
        return $query->where('author_id', $term);
    }

    public function scopeFindByTitle(Builder $query, string $term): Builder
    {
        return $query->where('title', 'like', '%'.strtoupper($term).'%');
    }

    public function scopeFindByCreatedDate(Builder $query, array $term): Builder
    {
        return $query->whereBetween('created_at', [$term[0], $term[1]]);
    }

    public function scopeResolved(Builder $query): Builder
    {
        return $query->whereNotNull('resolved_at');
    }

    public function scopeNonResolved(Builder $query): Builder
    {
        return $query->whereNull('resolved_at');
    }
}
