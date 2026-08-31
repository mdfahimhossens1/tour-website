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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->string('image');

            $table->string('link', 2048)->nullable();

            $table->string('position', 100);

            $table->unsignedBigInteger('views')->default(0);

            $table->unsignedBigInteger('clicks')->default(0);

            $table->date('start_date')->nullable();

            $table->date('end_date')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->index(['position', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};