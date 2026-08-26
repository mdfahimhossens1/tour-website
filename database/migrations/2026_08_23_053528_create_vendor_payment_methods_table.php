<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payment_methods', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('type');

            $table->string('account_number')->nullable();

            $table->string('api_key')->nullable();

            $table->string('secret_key')->nullable();

            $table->boolean('status')
                ->default(true);

            $table->text('description')
                ->nullable();

            $table->timestamps();

            $table->index([
                'vendor_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'vendor_payment_methods'
        );
    }
};