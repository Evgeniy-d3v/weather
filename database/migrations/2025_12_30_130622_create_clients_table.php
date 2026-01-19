<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->integer('chat_id')->unique();
            $table->string('user_full_name')->nullable();
            $table->string('user_username')->nullable();
            $table->string('city');
            $table->boolean('is_subscribed')->default(false);
            $table->integer('user_time_zone')->default(0);
            $table->json('sent_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clents');
    }
};
