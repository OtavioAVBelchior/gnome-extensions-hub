<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class CachedMergeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'extension_id',
        'platform',
        'mr_iid',
        'title',
        'description',
        'state',
        'author',
        'labels',
        'opened_at',
        'last_updated_at',
    ];

    protected $casts = [
        'labels' => 'array',
        'opened_at' => 'datetime',
        'last_updated_at' => 'datetime',
    ];

    public function extension(): BelongsTo
    {
        return $this->belongsTo(Extension::class);
    }

    public function scopeOpen(Builder $query)
    {
        return $query->where('state', 'opened');
    }
}