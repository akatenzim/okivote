<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::query()->select('id', 'name')->get();

        $query = Transaction::query()->with(['event', 'candidate']);

        // Filter Search (Invoice / Nama Voter / Phone)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhere('voter_name', 'LIKE', "%{$search}%")
                  ->orWhere('voter_phone', 'LIKE', "%{$search}%");
            });
        }

        // Filter Event
        if ($request->filled('event_id')) {
            $query->where('event_id', '=', $request->event_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', '=', $request->status);
        }

        $transactions = $query->latest()->paginate(15);

        return view('admin.transactions.index', compact('transactions', 'events'));
    }
}