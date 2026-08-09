<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'vote_price' => 'required|integer|min:0', // In IDR (Rupiah utuh)
            'voting_start_at' => 'required|date',
            'voting_end_at' => 'required|date|after:voting_start_at',
            'status' => 'required|in:DRAFT,COMING_SOON,ONGOING,FINISHED,SUSPENDED',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        if ($request->hasFile('poster')) {
            $validated['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        Event::create($validated);

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

        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $validated['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
    }
}