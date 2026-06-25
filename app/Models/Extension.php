<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Extension extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform',
        'repo_full_name',
        'extension_name',
        'uuid',
        'metadata',
        'supported_versions',
        'current_version',
        'last_synced_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'supported_versions' => 'array',
        'last_synced_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cachedIssues(): HasMany
    {
        return $this->hasMany(CachedIssue::class);
    }

    public function cachedMergeRequests(): HasMany
    {
        return $this->hasMany(CachedMergeRequest::class);
    }

    public function scopeForUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function getOpenIssuesCountAttribute(): int
    {
        return $this->cachedIssues()->where('state', 'opened')->count();
    }

    public function getOpenMrsCountAttribute(): int
    {
        return $this->cachedMergeRequests()->where('state', 'opened')->count();
    }
}