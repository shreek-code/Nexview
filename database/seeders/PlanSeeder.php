<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'id' => 'starter',
                'name' => 'Starter',
                'payment_model' => 'one_time',
                'billing_cycle' => null,
                'remote_access' => false,
                'network_restriction' => 'same_network_only',
                'limits' => [
                    'screens' => ['min' => 3, 'max' => 4],
                    'locations' => 0,
                    'storage_mb' => 100,
                    'storage_addon' => false,
                    'managers' => 0
                ],
                'analytics' => [
                    'proof_of_play' => false,
                    'screen_uptime' => false,
                    'campaign_delivery_report' => false,
                    'media_performance' => false,
                    'export_csv_pdf' => false
                ],
                'widgets' => [
                    'clock' => true,
                    'static_rss' => true,
                    'date_display' => true,
                    'weather' => false,
                    'live_rss' => false,
                    'alert_display' => false,
                    'social_feeds' => false,
                    'custom_data' => false
                ],
                'broadcasts' => [
                    'manual_override' => false,
                    'automated_alert_rules' => false
                ]
            ],
            [
                'id' => 'grow',
                'name' => 'Grow',
                'payment_model' => 'annual',
                'billing_cycle' => 'yearly',
                'remote_access' => true,
                'network_restriction' => null,
                'limits' => [
                    'screens' => ['min' => null, 'max' => 15],
                    'locations' => 5,
                    'storage_gb' => 5,
                    'storage_addon' => false,
                    'managers' => 5
                ],
                'analytics' => [
                    'proof_of_play' => true,
                    'screen_uptime' => true,
                    'campaign_delivery_report' => true,
                    'media_performance' => false,
                    'export_csv_pdf' => false
                ],
                'widgets' => [
                    'clock' => true,
                    'static_rss' => true,
                    'date_display' => true,
                    'weather' => false,
                    'live_rss' => false,
                    'alert_display' => false,
                    'social_feeds' => false,
                    'custom_data' => false
                ],
                'broadcasts' => [
                    'manual_override' => false,
                    'automated_alert_rules' => false
                ]
            ],
            [
                'id' => 'pro',
                'name' => 'Pro',
                'payment_model' => 'annual',
                'billing_cycle' => 'yearly',
                'remote_access' => true,
                'network_restriction' => null,
                'limits' => [
                    'screens' => ['min' => null, 'max' => null, 'unlimited' => true],
                    'locations' => ['unlimited' => true],
                    'storage_gb' => 20,
                    'storage_addon' => [
                        'enabled' => true,
                        'price_per_gb_per_month' => 20,
                        'currency' => 'INR',
                        'ceiling_gb' => 100,
                        'rollover' => false
                    ],
                    'managers' => ['unlimited' => true]
                ],
                'analytics' => [
                    'proof_of_play' => true,
                    'screen_uptime' => true,
                    'campaign_delivery_report' => true,
                    'media_performance' => true,
                    'export_csv_pdf' => true
                ],
                'widgets' => [
                    'clock' => true,
                    'static_rss' => true,
                    'date_display' => true,
                    'weather' => true,
                    'live_rss' => true,
                    'alert_display' => true,
                    'social_feeds' => true,
                    'custom_data' => true
                ],
                'broadcasts' => [
                    'manual_override' => true,
                    'automated_alert_rules' => true
                ]
            ]
        ];

        foreach ($plans as $index => $planData) {
            Plan::updateOrCreate(
                ['slug' => $planData['id']],
                [
                    'name' => $planData['name'],
                    'payment_model' => $planData['payment_model'],
                    'billing_cycle' => $planData['billing_cycle'],
                    'remote_access' => $planData['remote_access'],
                    'network_restriction' => $planData['network_restriction'],
                    'limits' => $planData['limits'],
                    'analytics' => $planData['analytics'],
                    'widgets' => $planData['widgets'],
                    'broadcasts' => $planData['broadcasts'],
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
