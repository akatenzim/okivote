<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('gateway');
            $table->string('gateway_event_id')->nullable();
            $table->string('gateway_reference')->nullable();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions');
            $table->string('event_type')->nullable();
            $table->json('payload');
            $table->boolean('signature_valid')->default(false);
            $table->enum('processing_status', ['PENDING', 'PROCESSED', 'FAILED', 'IGNORED'])->default('PENDING');
            $table->timestamp('received_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(['gateway', 'gateway_event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_webhooks');
    }
};