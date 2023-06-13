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
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->onDelete('cascade')->index();
            $table->foreignId('visitor_id');
            $table->string('url_path');
            $table->string('url_query')->nullable();
            $table->string('referer_path')->nullable();
            $table->string('referer_query')->nullable();
            $table->string('referer_domain')->nullable();
            $table->string('page_title');
            $table->timestamps();

            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
