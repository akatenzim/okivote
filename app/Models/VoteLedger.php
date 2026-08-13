<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteLedger extends Model
{
    use HasFactory;

    public $timestamps = false; // Memakai created_at default

    protected $fillable = [
        'event_id',
        'candidate_id',
        'transaction_id',
        'source_type',
        'source_reference',
        'vote_amount',
        'reason',
        'created_by_admin_id',
        'created_at',
    ];

    protected $casts = [
        'vote_amount' => 'integer',
        'created_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }
}