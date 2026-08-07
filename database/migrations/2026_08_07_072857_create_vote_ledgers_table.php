<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vote_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('candidate_id')->constrained('candidates');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions');
            $table->enum('source_type', ['PAID_TRANSACTION', 'FREE_VOTE', 'ADMIN_ADJUSTMENT', 'REFUND_ADJUSTMENT']);
            $table->string('source_reference')->nullable();
            $table->integer('vote_amount'); // Signed integer (+ / -)
            $table->text('reason')->nullable();
            $table->foreignId('created_by_admin_id')->nullable()->constrained('admins');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['event_id', 'candidate_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_ledgers');
    }
};