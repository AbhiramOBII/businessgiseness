<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // BG Community meetups / networking events
        Schema::create('community_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary', 300);
            $table->text('description')->nullable();
            $table->dateTime('starts_at')->index();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('venue_name')->nullable();
            $table->string('venue_address')->nullable();
            $table->string('map_link')->nullable();
            $table->unsignedInteger('capacity')->nullable(); // null = unlimited
            $table->string('cover_image')->nullable();
            $table->boolean('registration_open')->default(true);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('community_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('company');
            $table->string('phone', 30);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->unique(['community_event_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_registrations');
        Schema::dropIfExists('community_events');
    }
};
