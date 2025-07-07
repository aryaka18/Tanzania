<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'keyword',
        'position',
        'search_volume',
        'difficulty',
        'cpc',             
        'competition',       
        'intent',         
        'trend',          
        'category',       
        'tracking_data',
    ];

    protected $casts = [
        'tracking_data' => 'array',
        'cpc' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}