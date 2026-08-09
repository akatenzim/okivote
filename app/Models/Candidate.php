<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'category_id',
        'candidate_number',
        'name',
        'slug',
        'profile_photo_path',
        'cover_photo_path',
        'region',
        'biography',
        'social_media_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}