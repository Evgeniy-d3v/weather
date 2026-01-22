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
        Schema::create('weather_forecasts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('forecast_time');
            $table->integer('temperature');
            $table->integer('feels_like');
            $table->string('wind_direction');
            $table->integer('wind_speed');
            $table->string('cloud_cover');
            $table->string('weather_condition');
            $table->string('weather_description');
            $table->string('precipitation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_forecasts');
    }
};
