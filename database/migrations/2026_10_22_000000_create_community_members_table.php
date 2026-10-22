<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // People who registered to become part of BG Community
        Schema::create('community_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 30);
            $table->string('company');
            $table->string('vertical', 100);
            $table->json('expectations')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('email_verified_at')->nullable()->index();
            $table->timestamp('verification_sent_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_members');
    }
};
