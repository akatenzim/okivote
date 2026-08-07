<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->string('settlement_number')->unique();
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();
            $table->unsignedBigInteger('gross_revenue');
            $table->unsignedBigInteger('platform_fee');
            $table->unsignedBigInteger('gateway_fee');
            $table->bigInteger('other_adjustment')->default(0);
            $table->unsignedBigInteger('net_organizer_amount');
            $table->enum('status', ['DRAFT', 'CALCULATED', 'PROCESSING', 'PAID', 'CANCELLED'])->default('DRAFT');
            $table->text('notes')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->foreignId('created_by_admin_id')->constrained('admins');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};