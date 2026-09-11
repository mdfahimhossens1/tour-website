<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trial_settings', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Trial Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_enabled')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Trial Duration
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('trial_days')->default(14);

            /*
            |--------------------------------------------------------------------------
            | Payment Requirement
            |--------------------------------------------------------------------------
            */
            $table->boolean('requires_payment_method')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Auto Start
            |--------------------------------------------------------------------------
            */
            $table->boolean('auto_start')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Expiration Behaviour
            |--------------------------------------------------------------------------
            */
            $table->enum('expiration_action', [
                'cancel',
                'suspend',
                'downgrade',
            ])->default('suspend');

            /*
            |--------------------------------------------------------------------------
            | Grace Period
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('grace_period_days')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Reminder Settings
            |--------------------------------------------------------------------------
            */
            $table->boolean('send_expiry_reminder')->default(true);

            $table->unsignedInteger('reminder_days_before')->default(3);

            /*
            |--------------------------------------------------------------------------
            | Notifications
            |--------------------------------------------------------------------------
            */
            $table->boolean('send_trial_started_notification')->default(true);

            $table->boolean('send_trial_expiring_notification')->default(true);

            $table->boolean('send_trial_expired_notification')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Description / Notes
            |--------------------------------------------------------------------------
            */
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trial_settings');
    }
};