<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price_inr',
        'price_period',
        'stripe_price_id',
        'payment_model',
        'billing_cycle',
        'remote_access',
        'network_restriction',
        'limits',
        'analytics',
        'widgets',
        'broadcasts',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'remote_access' => 'boolean',
        'price_inr' => 'integer',
        'limits' => 'array',
        'analytics' => 'array',
        'widgets' => 'array',
        'broadcasts' => 'array',
    ];

    /**
     * Get the max number of screens allowed by this plan.
     * Reads from the JSON limits column: limits.screens.unlimited / limits.screens.max
     */
    public function getMaxScreensAttribute(): int
    {
        $limits = $this->limits ?? [];
        $screens = $limits['screens'] ?? [];

        if (!empty($screens['unlimited'])) {
            return PHP_INT_MAX;
        }

        return (int) ($screens['max'] ?? 1);
    }

    /**
     * Get the max storage in bytes allowed by this plan.
     * Reads from limits.storage_gb (in GB) and converts to bytes.
     * Falls back to limits.storage_mb (in MB) if storage_gb is not set.
     */
    public function getMaxStorageBytesAttribute(): int
    {
        $limits = $this->limits ?? [];

        if (isset($limits['storage_gb']) && $limits['storage_gb'] > 0) {
            return (int) ($limits['storage_gb'] * 1073741824); // GB to bytes
        }

        if (isset($limits['storage_mb']) && $limits['storage_mb'] > 0) {
            return (int) ($limits['storage_mb'] * 1048576); // MB to bytes
        }

        return 104857600; // 100MB default
    }

    /**
     * Get the formatted price display string.
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price_inr <= 0) {
            return 'Free';
        }

        $price = '₹' . number_format($this->price_inr);

        return match ($this->price_period) {
            'per_screen_month' => $price . '/screen/mo',
            'per_screen_year' => $price . '/screen/yr',
            'flat_monthly' => $price . '/mo',
            'flat_yearly' => $price . '/yr',
            'one_time' => $price . ' one-time',
            default => $price,
        };
    }

    /**
     * Get max locations allowed.
     */
    public function getMaxLocationsAttribute(): int
    {
        $limits = $this->limits ?? [];
        $locations = $limits['locations'] ?? 0;

        if (is_array($locations) && !empty($locations['unlimited'])) {
            return PHP_INT_MAX;
        }

        return (int) (is_array($locations) ? 0 : $locations);
    }

    /**
     * Get max managers allowed.
     */
    public function getMaxManagersAttribute(): int
    {
        $limits = $this->limits ?? [];
        $managers = $limits['managers'] ?? 0;

        if (is_array($managers) && !empty($managers['unlimited'])) {
            return PHP_INT_MAX;
        }

        return (int) (is_array($managers) ? 0 : $managers);
    }
}
