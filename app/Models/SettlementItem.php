<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettlementItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'settlement_id',
        'transaction_id',
        'gross_amount',
        'platform_fee',
        'gateway_fee',
        'net_amount',
        'created_at',
    ];

    protected $casts = [
        'gross_amount' => 'integer',
        'platform_fee' => 'integer',
        'gateway_fee' => 'integer',
        'net_amount' => 'integer',
        'created_at' => 'datetime',
    ];

    public function settlement(): BelongsTo
    {
        return $this->belongsTo(Settlement::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}