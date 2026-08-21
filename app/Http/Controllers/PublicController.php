<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\VoteLedger;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // Homepage: Event List dengan Filter Status
    public function index(Request $request)
    {
        $status = $request->get('status', 'ONGOING');

        $query = Event::where('status', '!=', 'DRAFT');

        // Pengecekan status yang valid, termasuk SUSPENDED
        if ($status === 'ONGOING') {
            // Tampilkan event yang ONGOING maupun SUSPENDED di bawah tab Berlangsung
            $query->whereIn('status', ['ONGOING', 'SUSPENDED']);
        } elseif (in_array($status, ['COMING_SOON', 'FINISHED', 'SUSPENDED'])) {
            $query->where('status', $status);
        }

        $events = $query->latest('updated_at')->paginate(9);

        return view('public.index', compact('events', 'status'));
    }

    // Detail Event & Daftar Kandidat
    public function showEvent(Request $request, $slug)
    {
        $event = Event::query()
            ->where('slug', '=', $slug)
            ->where('status', '!=', 'DRAFT')
            ->with(['categories', 'candidates' => function ($q) {
                $q->where('is_active', '=', true)->orderBy('sort_order', 'asc');
            }])
            ->firstOrFail();

        $selectedCategory = $request->get('category');

        // Total Vote Valid Seluruh Event dari Vote Ledger
        $totalEventVotes = (int) VoteLedger::query()
            ->where('event_id', '=', $event->id)
            ->sum('vote_amount');

        // Ambil kandidat beserta total vote sah masing-masing
        $candidates = $event->candidates
            ->when($selectedCategory, function ($collection) use ($selectedCategory) {
                return $collection->where('category_id', $selectedCategory);
            })
            ->map(function ($candidate) use ($totalEventVotes) {
                $candidateVotes = (int) VoteLedger::query()
                    ->where('candidate_id', '=', $candidate->id)
                    ->sum('vote_amount');

                $candidate->total_votes = $candidateVotes;
                $candidate->percentage = $totalEventVotes > 0
                    ? round(($candidateVotes / $totalEventVotes) * 100, 1)
                    : 0;

                return $candidate;
            })
            ->sortByDesc('total_votes');

        // ⚡ Ambil 10 transaksi vote PAID terbaru khusus event ini untuk Toast Notifikasi
        $recentVotes = Transaction::query()
            ->where('event_id', '=', $event->id)
            ->where('status', '=', 'PAID')
            ->with('candidate:id,name')
            ->latest('paid_at')
            ->take(10)
            ->get(['voter_name', 'is_anonymous', 'candidate_id', 'vote_quantity', 'paid_at'])
            ->map(function ($tx) {
                return [
                    'voter' => ($tx->is_anonymous || $tx->voter_name === 'Anonymous') ? 'Someone' : $tx->voter_name,
                    'candidate' => $tx->candidate->name ?? 'Kandidat',
                    'qty' => number_format($tx->vote_quantity),
                ];
            });

        return view('public.events.show', compact(
            'event',
            'candidates',
            'selectedCategory',
            'totalEventVotes',
            'recentVotes'
        ));
    }

    // Detail Kandidat + Metadata OpenGraph
    public function showCandidate($eventSlug, $candidateSlug)
    {
        $event = Event::query()
            ->where('slug', '=', $eventSlug)
            ->where('status', '!=', 'DRAFT')
            ->firstOrFail();

        $candidate = Candidate::query()
            ->where('event_id', '=', $event->id)
            ->where('slug', '=', $candidateSlug)
            ->where('is_active', '=', true)
            ->with('category')
            ->firstOrFail();

        // Hitung Vote Valid Kandidat Ini
        $candidateVotes = (int) VoteLedger::query()
            ->where('candidate_id', '=', $candidate->id)
            ->sum('vote_amount');

        // Supporter Feed (Hanya Transaksi PAID)
        $supporters = Transaction::query()
            ->where('candidate_id', '=', $candidate->id)
            ->where('status', '=', 'PAID')
            ->latest('paid_at')
            ->take(10)
            ->get();

        $ogData = [
            'title' => "Dukung {$candidate->name} (#{$candidate->candidate_number}) - {$event->name}",
            'description' => "Beri dukunganmu untuk {$candidate->name} pada ajang {$event->name} melalui OkiVote!",
            'image' => asset('storage/' . $candidate->profile_photo_path),
            'url' => url()->current(),
        ];

        return view('public.candidates.show', compact('event', 'candidate', 'candidateVotes', 'supporters', 'ogData'));
    }

    // Landing Page /register-event (CTA WhatsApp Admin)
    public function registerEvent()
    {
        return view('public.register-event');
    }
}