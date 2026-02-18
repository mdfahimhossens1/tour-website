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
        Schema::create('visitor_sessions', function (Blueprint $table) {

      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

      $table->string('ip_address', 45)->nullable();
      $table->string('country_code', 2)->nullable();     // BD, US
      $table->string('country_name', 80)->nullable();    // Bangladesh, United States
      $table->string('city', 80)->nullable();            // optional

      $table->string('last_url')->nullable();
      $table->timestamp('last_seen_at')->nullable();

      $table->timestamps();

      $table->index(['country_code', 'last_seen_at']);
      $table->index(['user_id', 'last_seen_at']);
      $table->index(['ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_sessions');
    }
};
