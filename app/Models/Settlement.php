<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'settlement_number',
        'period_start',
        'period_end',
        'gross_revenue',
        'platform_fee',
        'gateway_fee',
        'other_adjustment',
        'net_organizer_amount',
        'status',
        'notes',
        'settled_at',
        'created_by_admin_id',
    ];

    protected $casts = [
        'gross_revenue' => 'integer',
        'platform_fee' => 'integer',
        'gateway_fee' => 'integer',
        'other_adjustment' => 'integer',
        'net_organizer_amount' => 'integer',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'settled_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SettlementItem::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }
}