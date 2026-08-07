<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settlement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained('settlements')->cascadeOnDelete();
            $table->foreignId('transaction_id')->unique()->constrained('transactions');
            $table->unsignedBigInteger('gross_amount');
            $table->unsignedBigInteger('platform_fee');
            $table->unsignedBigInteger('gateway_fee');
            $table->unsignedBigInteger('net_amount');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlement_items');
    }
};