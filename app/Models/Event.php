<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'organizer_name',
        'organizer_contact',
        'description',
        'poster_path',
        'banner_path',
        'location',
        'event_date',
        'voting_start_at',
        'voting_end_at',
        'voting_type',
        'vote_price',
        'leaderboard_enabled',
        'leaderboard_display',
        'status',
        'terms',
        'published_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'voting_start_at' => 'datetime',
        'voting_end_at' => 'datetime',
        'published_at' => 'datetime',
        'leaderboard_enabled' => 'boolean',
        'vote_price' => 'integer',
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(EventCategory::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}