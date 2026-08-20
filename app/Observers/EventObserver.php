<?php

namespace App\Observers;

use App\Models\Event;
use App\Services\AuditLogService;

class EventObserver
{
    public function updated(Event $event): void
    {
        // 1. Deteksi Perubahan Harga Vote (VOTE_PRICE_CHANGED)
        if ($event->isDirty('vote_price')) {
            AuditLogService::log(
                action: 'VOTE_PRICE_CHANGED',
                entityType: Event::class,
                entityId: $event->id,
                oldValues: ['vote_price' => $event->getOriginal('vote_price')],
                newValues: ['vote_price' => $event->vote_price],
                reason: "Perubahan harga vote pada event {$event->name}"
            );
        }

        // 2. Deteksi Perubahan Periode Voting (VOTING_PERIOD_CHANGED)
        if ($event->isDirty('voting_start_at') || $event->isDirty('voting_end_at')) {
            AuditLogService::log(
                action: 'VOTING_PERIOD_CHANGED',
                entityType: Event::class,
                entityId: $event->id,
                oldValues: [
                    'voting_start_at' => $event->getOriginal('voting_start_at'),
                    'voting_end_at' => $event->getOriginal('voting_end_at'),
                ],
                newValues: [
                    'voting_start_at' => $event->voting_start_at,
                    'voting_end_at' => $event->voting_end_at,
                ],
                reason: "Perubahan jadwal/periode voting pada event {$event->name}"
            );
        }
    }
}