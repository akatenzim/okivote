<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\VoteLedger;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Core Summary Metrics
        $totalEvents = Event::query()->count();
        $activeEvents = Event::query()->where('status', '=', 'ONGOING')->count();
        $totalCandidates = Candidate::query()->where('is_active', '=', true)->count();

        // Total Vote Valid dari Vote Ledger
        $totalValidVotes = (int) VoteLedger::query()->sum('vote_amount');

        // Total GMV / Transaksi Terbayar (PAID)
        $totalGmv = (int) Transaction::query()->where('status', '=', 'PAID')->sum('grand_total');

        // Transaksi Hari Ini
        $transactionsTodayCount = Transaction::query()->whereDate('created_at', now()->today())->count();
        $gmvToday = (int) Transaction::query()->where('status', '=', 'PAID')->whereDate('paid_at', now()->today())->sum('grand_total');

        // 2. Recent Transactions List
        $recentTransactions = Transaction::query()
            ->with(['event', 'candidate'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalEvents',
            'activeEvents',
            'totalCandidates',
            'totalValidVotes',
            'totalGmv',
            'transactionsTodayCount',
            'gmvToday',
            'recentTransactions'
        ));
    }
}