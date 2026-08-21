<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventCategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ProfileController;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\PublicController;

use App\Services\Gateways\DummyPaymentGateway;
use App\Services\VoteService;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Voter Facing - Zero Authentication Wall)
|--------------------------------------------------------------------------
| Route publik yang dapat diakses langsung oleh pemilih tanpa proses registrasi
| atau login akun untuk menjaga tingkat konversi voting yang tinggi.
*/

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/events', [PublicController::class, 'index'])->name('public.events.index');
Route::get('/events/{slug}', [PublicController::class, 'showEvent'])->name('public.events.show');
Route::get('/events/{eventSlug}/candidates/{candidateSlug}', [PublicController::class, 'showCandidate'])->name('public.candidates.show');
Route::get('/register-event', [PublicController::class, 'registerEvent'])->name('public.register-event');

/*
|--------------------------------------------------------------------------
| Checkout & Transaksi Routes
|--------------------------------------------------------------------------
| Endpoint pemrosesan pembelian vote dan halaman instruksi/keberhasilan pembayaran.
| Diterapkan rate limiting (10 request/menit) untuk mencegah serangan bot spam checkout.
*/

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('public.checkout.store');

Route::get('/checkout/{invoiceNumber}', [CheckoutController::class, 'show'])
    ->name('public.checkout.success');

/*
|--------------------------------------------------------------------------
| Payment Gateway Webhook & Simulation Routes
|--------------------------------------------------------------------------
| Callback otomatis dari penyedia payment gateway untuk pembaruan status transaksi.
| Route simulasi menggunakan internal controller call guna menghindari deadlock
| pada lingkungan single-threaded lokal (php artisan serve).
*/

Route::post('/webhooks/payment', [PaymentWebhookController::class, 'handle'])
    ->name('webhook.payment');

if (app()->environment('local', 'testing')) {
    Route::post('/webhooks/dummy-simulate', function (HttpRequest $request, DummyPaymentGateway $gateway, VoteService $voteService) {
        $simulatedRequest = HttpRequest::create(
            route('webhook.payment'),
            'POST',
            [
                'event_id' => 'EVT-' . rand(1000, 9999),
                'invoice_number' => $request->input('invoice_number'),
                'amount' => (int) $request->input('amount'),
                'status' => 'PAID',
                'gateway_reference' => 'REF-' . rand(10000, 99999),
            ],
            [],
            [],
            ['HTTP_X-Mock-Signature' => config('services.dummy_gateway.secret', 'okivote-secret')]
        );

        $controller = app(PaymentWebhookController::class);
        $response = $controller->handle($simulatedRequest, $gateway, $voteService);

        return response()->json([
            'status' => 'SUCCESS_SIMULATION',
            'message' => 'Status pembayaran berhasil diperbarui menjadi PAID dan Vote Ledger diterbitkan.',
            'webhook_response' => json_decode($response->getContent(), true),
        ]);
    })->name('webhook.dummy');
}

/*
|--------------------------------------------------------------------------
| Administrator Management Panel Routes
|--------------------------------------------------------------------------
| Seluruh endpoint administrasi platform OkiVote yang terisolasi dengan
| guard khusus admin, proteksi rate limiting pada halaman login, serta audit trail.
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Autentikasi Admin (Guest)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

        // Pembatasan percobaan login (maksimal 5 kali percobaan per menit)
        Route::post('/login', [LoginController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.post');
    });

    // Area Terproteksi Administrator (Authenticated)
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Manajemen Event & Entitas
        Route::resource('events', EventController::class);
        Route::resource('categories', EventCategoryController::class)->except(['show']);
        Route::resource('candidates', CandidateController::class);

        // Rekapitulasi Transaksi, Settlement & Audit Logs
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/settlements', [SettlementController::class, 'index'])->name('settlements.index');
        Route::get('/settlements/create', [SettlementController::class, 'create'])->name('settlements.create');
        Route::post('/settlements', [SettlementController::class, 'store'])->name('settlements.store');
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

        // Profil & Kredensial Administrator
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});

require __DIR__ . '/auth.php';