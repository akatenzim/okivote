<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount(['candidates', 'categories'])->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organizer_name' => 'required|string|max:255',
            'organizer_contact' => 'required|string|max:100',
            'description' => 'nullable|string',
            'vote_price' => 'required|integer|min:0',
            'voting_start_at' => 'required|date',
            'voting_end_at' => 'required|date|after:voting_start_at',
            'status' => 'required|in:DRAFT,COMING_SOON,ONGOING,FINISHED,SUSPENDED',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        // Auto-set published_at jika status event bukan DRAFT saat dibuat
        if ($validated['status'] !== 'DRAFT') {
            $validated['published_at'] = now();
        }

        // Upload poster jika ada
        if ($request->hasFile('poster')) {
            $validated['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event = Event::create($validated);

        // Audit Log
        AuditLogService::log(
            action: 'CREATE_EVENT',
            entityType: get_class($event),
            entityId: $event->id,
            oldValues: null,
            newValues: [
                'name' => $event->name,
                'status' => $event->status,
                'vote_price' => $event->vote_price,
            ],
            reason: 'Membuat event baru'
        );

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat!');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organizer_name' => 'required|string|max:255',
            'organizer_contact' => 'required|string|max:100',
            'description' => 'nullable|string',
            'vote_price' => 'required|integer|min:0',
            'voting_start_at' => 'required|date',
            'voting_end_at' => 'required|date|after:voting_start_at',
            'status' => 'required|in:DRAFT,COMING_SOON,ONGOING,FINISHED,SUSPENDED',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $oldValues = [
            'name' => $event->name,
            'status' => $event->status,
            'vote_price' => $event->vote_price,
            'published_at' => $event->published_at,
        ];

        // Set published_at jika sebelumnya null dan status diubah dari DRAFT ke publik
        if ($validated['status'] !== 'DRAFT' && empty($event->published_at)) {
            $validated['published_at'] = now();
        }

        // Penanganan penggantian berkas poster
        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $validated['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($validated);

        // Audit Log jika ada perubahan atribut penting (harga vote / status / nama)
        AuditLogService::log(
            action: 'UPDATE_EVENT',
            entityType: get_class($event),
            entityId: $event->id,
            oldValues: $oldValues,
            newValues: [
                'name' => $event->name,
                'status' => $event->status,
                'vote_price' => $event->vote_price,
                'published_at' => $event->published_at,
            ],
            reason: 'Pembaruan detail dan konfigurasi event'
        );

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
    }
}