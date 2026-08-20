<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'event_id',
        'candidate_id',
        'voter_name',
        'voter_phone',
        'support_message',
        'is_anonymous',
        'vote_quantity',
        'vote_price',
        'subtotal',
        'service_fee',
        'payment_fee',
        'grand_total',
        'payment_method',
        'status',
        'expires_at',
        'paid_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'vote_quantity' => 'integer',
        'vote_price' => 'integer',
        'subtotal' => 'integer',
        'service_fee' => 'integer',
        'payment_fee' => 'integer',
        'grand_total' => 'integer',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function voteLedger(): HasOne
    {
        return $this->hasOne(VoteLedger::class);
    }

    public function settlementItem(): HasOne
    {
        return $this->hasOne(SettlementItem::class);
    }
}