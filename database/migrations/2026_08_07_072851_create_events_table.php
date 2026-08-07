<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('organizer_name');
            $table->string('organizer_contact');
            $table->text('description')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->string('location')->nullable();
            $table->date('event_date')->nullable();
            $table->timestamp('voting_start_at')->nullable();
            $table->timestamp('voting_end_at')->nullable();
            $table->enum('voting_type', ['PAID', 'FREE'])->default('PAID');
            $table->unsignedBigInteger('vote_price')->default(0); // In IDR (Rupiah utuh)
            $table->boolean('leaderboard_enabled')->default(true);
            $table->enum('leaderboard_display', ['TOTAL', 'PERCENTAGE', 'BOTH'])->default('BOTH');
            $table->enum('status', ['DRAFT', 'COMING_SOON', 'ONGOING', 'FINISHED', 'SUSPENDED'])->default('DRAFT');
            $table->text('terms')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index(['voting_start_at', 'voting_end_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};