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
Schema::create('policies', function (Blueprint $table) {
    $table->id();
    $table->string('type')->unique(); // privacy, terms, refund, cookies
    $table->string('title_en');
    $table->string('title_bn')->nullable();

    $table->longText('content_en');
    $table->longText('content_bn')->nullable();

    $table->boolean('status')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
