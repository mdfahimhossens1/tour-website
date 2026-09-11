<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrialSetting extends Model
{
    protected $table = 'trial_settings';

    protected $fillable = [
        'is_enabled',
        'trial_days',
        'requires_payment_method',
        'auto_start',
        'expiration_action',
        'grace_period_days',
        'send_expiry_reminder',
        'reminder_days_before',
        'send_trial_started_notification',
        'send_trial_expiring_notification',
        'send_trial_expired_notification',
        'description',
    ];

    protected $attributes = [
        'is_enabled' => true,
        'trial_days' => 14,
        'requires_payment_method' => false,
        'auto_start' => true,
        'expiration_action' => 'suspend',
        'grace_period_days' => 0,
        'send_expiry_reminder' => true,
        'reminder_days_before' => 3,
        'send_trial_started_notification' => true,
        'send_trial_expiring_notification' => true,
        'send_trial_expired_notification' => true,
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'trial_days' => 'integer',
            'requires_payment_method' => 'boolean',
            'auto_start' => 'boolean',
            'grace_period_days' => 'integer',
            'send_expiry_reminder' => 'boolean',
            'reminder_days_before' => 'integer',
            'send_trial_started_notification' => 'boolean',
            'send_trial_expiring_notification' => 'boolean',
            'send_trial_expired_notification' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Get Current Trial Settings
    |--------------------------------------------------------------------------
    */

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'is_enabled' => true,
                'trial_days' => 14,
                'requires_payment_method' => false,
                'auto_start' => true,
                'expiration_action' => 'suspend',
                'grace_period_days' => 0,
                'send_expiry_reminder' => true,
                'reminder_days_before' => 3,
                'send_trial_started_notification' => true,
                'send_trial_expiring_notification' => true,
                'send_trial_expired_notification' => true,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    public function isDisabled(): bool
    {
        return ! $this->is_enabled;
    }

    public function requiresPaymentMethod(): bool
    {
        return $this->requires_payment_method;
    }

    public function autoStarts(): bool
    {
        return $this->auto_start;
    }

    /*
    |--------------------------------------------------------------------------
    | Expiration Helpers
    |--------------------------------------------------------------------------
    */

    public function shouldCancelOnExpiration(): bool
    {
        return $this->expiration_action === 'cancel';
    }

    public function shouldSuspendOnExpiration(): bool
    {
        return $this->expiration_action === 'suspend';
    }

    public function shouldDowngradeOnExpiration(): bool
    {
        return $this->expiration_action === 'downgrade';
    }

    public function hasGracePeriod(): bool
    {
        return $this->grace_period_days > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Reminder Helpers
    |--------------------------------------------------------------------------
    */

    public function shouldSendExpiryReminder(): bool
    {
        return $this->send_expiry_reminder;
    }

    public function shouldSendTrialStartedNotification(): bool
    {
        return $this->send_trial_started_notification;
    }

    public function shouldSendTrialExpiringNotification(): bool
    {
        return $this->send_trial_expiring_notification;
    }

    public function shouldSendTrialExpiredNotification(): bool
    {
        return $this->send_trial_expired_notification;
    }

    /*
    |--------------------------------------------------------------------------
    | Label Helpers
    |--------------------------------------------------------------------------
    */

    public function getExpirationActionLabel(): string
    {
        return match ($this->expiration_action) {
            'cancel' => 'Cancel Subscription',
            'suspend' => 'Suspend Subscription',
            'downgrade' => 'Downgrade Subscription',
            default => ucfirst(
                str_replace('_', ' ', $this->expiration_action ?? 'Unknown')
            ),
        };
    }

    public function getTrialDurationLabel(): string
    {
        return $this->trial_days . ' ' .
            ($this->trial_days === 1 ? 'Day' : 'Days');
    }

    public function getGracePeriodLabel(): string
    {
        if ($this->grace_period_days <= 0) {
            return 'No Grace Period';
        }

        return $this->grace_period_days . ' ' .
            ($this->grace_period_days === 1 ? 'Day' : 'Days');
    }

    public function getReminderLabel(): string
    {
        if (! $this->send_expiry_reminder) {
            return 'Disabled';
        }

        return $this->reminder_days_before . ' ' .
            ($this->reminder_days_before === 1 ? 'Day' : 'Days') .
            ' Before Expiry';
    }
}