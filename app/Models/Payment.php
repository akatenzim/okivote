<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'gateway',
        'gateway_reference',
        'payment_method',
        'payment_channel',
        'amount',
        'status',
        'paid_at',
        'raw_response',
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
        'raw_response' => 'array',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}