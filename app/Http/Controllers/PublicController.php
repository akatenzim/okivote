<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Event;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // Homepage: Event List dengan Filter Status
    public function index(Request $request)
    {
        $status = $request->get('status', 'ONGOING');

        $query = Event::where('status', '!=', 'DRAFT')
            ->whereNotNull('published_at');

        if (in_array($status, ['ONGOING', 'COMING_SOON', 'FINISHED'])) {
            $query->where('status', $status);
        }

        $events = $query->latest('published_at')->paginate(9);

        return view('public.index', compact('events', 'status'));
    }

    // Detail Event & Daftar Kandidat
    public function showEvent(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)
            ->where('status', '!=', 'DRAFT')
            ->with(['categories', 'candidates' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order', 'asc');
            }])
            ->firstOrFail();

        $selectedCategory = $request->get('category');

        $candidates = $event->candidates->when($selectedCategory, function ($collection) use ($selectedCategory) {
            return $collection->where('category_id', $selectedCategory);
        });

        return view('public.events.show', compact('event', 'candidates', 'selectedCategory'));
    }

    // Detail Kandidat + Metadata OpenGraph
    public function showCandidate($eventSlug, $candidateSlug)
    {
        $event = Event::where('slug', $eventSlug)
            ->where('status', '!=', 'DRAFT')
            ->firstOrFail();

        $candidate = Candidate::where('event_id', $event->id)
            ->where('slug', $candidateSlug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        // Metadata OpenGraph untuk WhatsApp / Social Sharing
        $ogData = [
            'title' => "Dukung {$candidate->name} (#{$candidate->candidate_number}) - {$event->name}",
            'description' => "Beri dukunganmu untuk {$candidate->name} pada ajang {$event->name} melalui OkiVote!",
            'image' => asset('storage/' . $candidate->profile_photo_path),
            'url' => url()->current(),
        ];

        return view('public.candidates.show', compact('event', 'candidate', 'ogData'));
    }

    // Landing Page /register-event (CTA WhatsApp Admin)
    public function registerEvent()
    {
        return view('public.register-event');
    }
}