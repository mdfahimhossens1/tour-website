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
Schema::create('resorts', function (Blueprint $table) {

    $table->id();
    $table->foreignId('vendor_id')
        ->constrained()
        ->cascadeOnDelete();
    $table->foreignId('destination_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('short_description')->nullable();
    $table->longText('description')->nullable();
    $table->string('division')->nullable();
    $table->string('district')->nullable();
    $table->string('area')->nullable();
    $table->text('address')->nullable();
    $table->string('google_map')->nullable();
    $table->decimal('latitude',10,7)->nullable();
    $table->decimal('longitude',10,7)->nullable();
    $table->string('featured_image')->nullable();
    $table->string('cover_image')->nullable();
    $table->time('check_in')->nullable();
    $table->time('check_out')->nullable();
    $table->decimal('rating',3,2)->default(0);
    $table->integer('total_reviews')->default(0);
    $table->boolean('is_featured')->default(false);
    $table->boolean('is_verified')->default(false);
    $table->enum('status',[
        'pending',
        'approved',
        'rejected'
    ])->default('pending');
    $table->string('meta_title')->nullable();
    $table->text('meta_description')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resorts');
    }
};
