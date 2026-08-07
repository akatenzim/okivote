<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('candidate_id')->constrained('candidates');
            $table->string('voter_name');
            $table->string('voter_phone');
            $table->text('support_message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->unsignedInteger('vote_quantity');
            $table->unsignedBigInteger('vote_price'); // Snapshot harga per vote saat transaksi
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('service_fee')->default(0);
            $table->unsignedBigInteger('payment_fee')->default(0);
            $table->unsignedBigInteger('grand_total');
            $table->string('payment_method')->nullable();
            $table->enum('status', ['PENDING', 'PAID', 'EXPIRED', 'FAILED', 'CANCELLED', 'REFUNDED'])->default('PENDING');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'candidate_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};