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
    Schema::create('team_members', function (Blueprint $table) {

        $table->id();

        $table->string('name');

        $table->string('designation_en');
        $table->string('designation_bn');

        $table->string('image')->nullable();

        $table->string('email')->nullable();
        $table->string('phone')->nullable();

        $table->string('facebook')->nullable();
        $table->string('linkedin')->nullable();

        $table->text('bio_en')->nullable();
        $table->text('bio_bn')->nullable();

        $table->boolean('status')->default(true);

        $table->integer('sort_order')->default(0);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
