<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'url',
        'analysis_results',
        'last_analyzed_at',
        'seo_score',
        'keywords',
    ];

    protected $casts = [
        'analysis_results' => 'array',
        'keywords' => 'array',
        'last_analyzed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    public function getSeoScoreColorAttribute(): string
    {
        if ($this->seo_score >= 80) return 'text-green-600';
        if ($this->seo_score >= 60) return 'text-yellow-600';
        return 'text-red-600';
    }
}