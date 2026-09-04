<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rules', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Tax Information
            |--------------------------------------------------------------------------
            */
            $table->string('name');

            $table->string('code')
                ->unique();

            $table->text('description')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Tax Type
            |--------------------------------------------------------------------------
            | percentage = e.g. 5%
            | fixed      = e.g. 100
            |--------------------------------------------------------------------------
            */
            $table->enum('type', [
                'percentage',
                'fixed',
            ])->default('percentage');

            /*
            |--------------------------------------------------------------------------
            | Tax Rate / Amount
            |--------------------------------------------------------------------------
            */
            $table->decimal('rate', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Where Tax Applies
            |--------------------------------------------------------------------------
            */
            $table->enum('applies_to', [
                'booking',
                'vendor_payout',
                'both',
            ])->default('booking');

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Priority
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('priority')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Effective Dates
            |--------------------------------------------------------------------------
            */
            $table->date('starts_at')
                ->nullable();

            $table->date('ends_at')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('type');
            $table->index('applies_to');
            $table->index('is_active');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_rules');
    }
};