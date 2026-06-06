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
        $table->string('link')->nullable();
        $table->enum('position', [
            'homepage_banner',
            'sidebar',
            'package_page',
            'footer'
        ]);
        $table->integer('views')->default(0);
        $table->integer('clicks')->default(0);
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->boolean('status')->default(1);
        $table->timestamps();
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
