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
        Schema::create('ship', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('mmsi');
            $table->unsignedInteger('stationId');
            $table->unsignedInteger('speed');
            $table->double('lon', 8, 5);
            $table->double('lat', 8, 5);
            $table->unsignedInteger('course');
            $table->unsignedInteger('heading');
            $table->string('rot')->default("");
            $table->unsignedSmallInteger('status');
            $table->unsignedInteger('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ship');
    }
};
