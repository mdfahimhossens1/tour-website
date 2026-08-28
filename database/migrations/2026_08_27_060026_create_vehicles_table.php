<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Vendor
            |--------------------------------------------------------------------------
            */

            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Vehicle Information
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('slug')->unique();

            $table->string('vehicle_type');

            $table->string('brand')->nullable();

            $table->string('model')->nullable();

            $table->string('registration_number')
                ->nullable()
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Capacity
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('passenger_capacity')
                ->default(1);


            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            $table->string('division')->nullable();

            $table->string('district')->nullable();

            $table->string('area')->nullable();

            $table->text('address')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            $table->decimal('price_per_day', 12, 2)
                ->default(0);

            $table->decimal('price_per_hour', 12, 2)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Vehicle Details
            |--------------------------------------------------------------------------
            */

            $table->text('description')->nullable();

            $table->string('featured_image')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Options
            |--------------------------------------------------------------------------
            */

            $table->boolean('with_driver')
                ->default(false);

            $table->boolean('is_featured')
                ->default(false);

            $table->boolean('is_verified')
                ->default(false);


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'inactive',
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'vendor_id',
                'vehicle_type',
                'status',
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
