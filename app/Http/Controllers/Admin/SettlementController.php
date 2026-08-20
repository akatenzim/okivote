<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Settlement;
use App\Models\SettlementItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettlementController extends Controller
{
    public function index()
    {
        $settlements = Settlement::query()->with(['event', 'admin'])->latest()->paginate(10);
        return view('admin.settlements.index', compact('settlements'));
    }

    public function create()
    {
        $events = Event::query()->where('status', '!=', 'DRAFT')->get();
        return view('admin.settlements.create', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'platform_fee_percent' => 'required|numeric|min:0|max:100', // Misal 15%
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $eventId = $validated['event_id'];

            // 1. Ambil Transaksi PAID dari Event ini yang BELUM PERNAH di-settle
            $unsettledTransactions = Transaction::query()
                ->where('event_id', '=', $eventId)
                ->where('status', '=', 'PAID')
                ->whereDoesntHave('settlementItem')
                ->get();

            if ($unsettledTransactions->isEmpty()) {
                return back()->with('error', 'Tidak ada transaksi PAID baru yang dapat di-settle untuk event ini!');
            }

            // 2. Hitung Rekapitulasi Keuangan
            $grossRevenue = (int) $unsettledTransactions->sum('subtotal');
            $platformFeePercent = (float) $validated['platform_fee_percent'];
            $platformFee = (int) round(($grossRevenue * $platformFeePercent) / 100);
            $gatewayFee = (int) $unsettledTransactions->sum('payment_fee');
            $netOrganizerAmount = $grossRevenue - $platformFee - $gatewayFee;

            $settlementNumber = 'STL-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // 3. Simpan Settlement Record
            $settlement = Settlement::create([
                'event_id' => $eventId,
                'settlement_number' => $settlementNumber,
                'gross_revenue' => $grossRevenue,
                'platform_fee' => $platformFee,
                'gateway_fee' => $gatewayFee,
                'net_organizer_amount' => $netOrganizerAmount,
                'status' => 'PAID',
                'notes' => $validated['notes'] ?? null,
                'settled_at' => now(),
                'created_by_admin_id' => auth()->guard('admin')->id(),
            ]);

            // 4. Hubungkan Setiap Transaksi ke Settlement Items
            foreach ($unsettledTransactions as $tx) {
                $txGross = (int) $tx->subtotal;
                $txPlatformFee = (int) round(($txGross * $platformFeePercent) / 100);

                SettlementItem::create([
                    'settlement_id' => $settlement->id,
                    'transaction_id' => $tx->id,
                    'gross_amount' => $txGross,
                    'platform_fee' => $txPlatformFee,
                    'gateway_fee' => (int) $tx->payment_fee,
                    'net_amount' => $txGross - $txPlatformFee - (int) $tx->payment_fee,
                    'created_at' => now(),
                ]);
            }

            return redirect()->route('admin.settlements.index')->with('success', "Settlement {$settlementNumber} berhasil dicatat!");
        });
    }
}