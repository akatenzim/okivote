<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentWebhookController;

use App\Services\Gateways\DummyPaymentGateway;

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventCategoryController;
use App\Http\Controllers\Admin\CandidateController;

use App\Services\VoteService;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request as HttpRequest;

// Public Routes (Zero Auth Wall)
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/events', [PublicController::class, 'index'])->name('public.events.index');
Route::get('/events/{slug}', [PublicController::class, 'showEvent'])->name('public.events.show');
Route::get('/events/{eventSlug}/candidates/{candidateSlug}', [PublicController::class, 'showCandidate'])->name('public.candidates.show');
Route::get('/register-event', [PublicController::class, 'registerEvent'])->name('public.register-event');

// Checkout Public Routes
Route::post('/checkout', [CheckoutController::class, 'store'])->name('public.checkout.store');
Route::get('/checkout/{invoiceNumber}', [CheckoutController::class, 'show'])->name('public.checkout.success');

// Webhook Route (Exclude CSRF Protection if needed)
Route::post('/webhooks/payment', [PaymentWebhookController::class, 'handle'])->name('webhook.payment');

// Route khusus simulasi testing webhook lokal (Direct Method Call - No Deadlock)
Route::post('/webhooks/dummy-simulate', function (HttpRequest $request, DummyPaymentGateway $gateway, VoteService $voteService) {
    // 1. Buat Simulated Request Object dengan Signature Valid Header
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

    // 2. Panggil controller dengan menyertakan ketiga parameter
    $controller = app(PaymentWebhookController::class);
    $response = $controller->handle($simulatedRequest, $gateway, $voteService);

    return response()->json([
        'status' => 'SUCCESS_SIMULATION',
        'message' => 'Status pembayaran berhasil diubah menjadi PAID dan Vote Ledger telah diterbitkan!',
        'webhook_response' => json_decode($response->getContent(), true),
    ]);
})->name('webhook.dummy');

// Admin Routes (Tanpa Link Publik)
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    });

    // Authenticated Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Sprint 2 Routes
        Route::resource('events', EventController::class);
        Route::resource('categories', EventCategoryController::class)->except(['show']);
        Route::resource('candidates', CandidateController::class);
    });
});

require __DIR__.'/auth.php';
