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
            $table->unsignedBigInteger('city_id')->nullable();
            $table->boolean('is_subscribed')->default(false);
            $table->json('sent_time')->default(json_encode([]));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
