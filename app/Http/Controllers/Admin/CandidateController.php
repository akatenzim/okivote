<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCandidateRequest;
use App\Models\Candidate;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::select('id', 'name')->get();
        $query = Candidate::with(['event', 'category']);

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $candidates = $query->latest()->paginate(15);

        return view('admin.candidates.index', compact('candidates', 'events'));
    }

    public function create(Request $request)
    {
        $events = Event::with('categories')->get();
        $selectedEventId = $request->get('event_id');
        return view('admin.candidates.create', compact('events', 'selectedEventId'));
    }

    public function store(StoreCandidateRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo_path'] = $request->file('profile_photo')->store('candidates', 'public');
        }

        Candidate::create($validated);

        return redirect()->route('admin.candidates.index', ['event_id' => $request->event_id])
            ->with('success', 'Kandidat berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit kandidat
     */
    public function edit(Candidate $candidate)
    {
        $events = Event::orderBy('name', 'asc')->get();
        return view('admin.candidates.edit', compact('candidate', 'events'));
    }

    /**
     * Perbarui data kandidat di database
     */
    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'candidate_number' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'event_id' => $request->event_id,
            'candidate_number' => $request->candidate_number,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
            'region' => $request->region,
            'biography' => $request->biography,
            'is_active' => $request->is_active,
        ];

        // Jika ada unggahan foto profil baru
        if ($request->hasFile('profile_photo')) {
            if ($candidate->profile_photo_path) {
                Storage::disk('public')->delete($candidate->profile_photo_path);
            }
            $data['profile_photo_path'] = $request->file('profile_photo')->store('candidates', 'public');
        }

        $candidate->update($data);

        return redirect()->route('admin.candidates.index')->with('success', 'Data kandidat berhasil diperbarui!');
    }

    public function destroy(Candidate $candidate)
    {
        // Immutable Constraint Rule: Jika kandidat sudah pernah transaksi, tidak boleh hard-delete
        if ($candidate->transactions()->exists()) {
            $candidate->update(['is_active' => false]);
            return back()->with('error', 'Kandidat memiliki riwayat transaksi! Status diubah menjadi Non-Aktif.');
        }

        if ($candidate->profile_photo_path) {
            Storage::disk('public')->delete($candidate->profile_photo_path);
        }

        $candidate->delete();

        return back()->with('success', 'Kandidat berhasil dihapus!');
    }
}