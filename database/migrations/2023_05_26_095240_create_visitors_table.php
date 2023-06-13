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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->foreignId('website_id')->onDelete('cascade')->index();
            $table->string('referer_domain')->nullable();
            $table->string('browser');
            $table->string('os');
            $table->string('device');
            $table->string('screen');
            $table->string('language');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->decimal('lat', 8, 6)->nullable();
            $table->decimal('lon', 9, 6)->nullable();
            $table->timestamps();

            $table->index(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
