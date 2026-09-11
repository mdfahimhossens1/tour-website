<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [

            // =====================================================
            // TOUR MANAGEMENT
            // =====================================================

            [
                'name' => 'Tour Management',
                'slug' => 'tour-management',
                'key' => 'tour_management',
                'description' => 'Create, manage and publish tour packages.',
                'category' => 'tour_management',
                'icon' => 'fas fa-map-marked-alt',
                'sort_order' => 10,
            ],

            [
                'name' => 'Tour Dates',
                'slug' => 'tour-dates',
                'key' => 'tour_dates',
                'description' => 'Manage tour schedules and available dates.',
                'category' => 'tour_management',
                'icon' => 'fas fa-calendar-alt',
                'sort_order' => 20,
            ],

            [
                'name' => 'Destination Management',
                'slug' => 'destination-management',
                'key' => 'destination_management',
                'description' => 'Manage destinations used across the marketplace.',
                'category' => 'tour_management',
                'icon' => 'fas fa-location-dot',
                'sort_order' => 30,
            ],

            // =====================================================
            // BOOKING MANAGEMENT
            // =====================================================

            [
                'name' => 'Booking Management',
                'slug' => 'booking-management',
                'key' => 'booking_management',
                'description' => 'Manage tour and travel bookings.',
                'category' => 'booking_management',
                'icon' => 'fas fa-calendar-check',
                'sort_order' => 40,
            ],

            [
                'name' => 'Refund Management',
                'slug' => 'refund-management',
                'key' => 'refund_management',
                'description' => 'Manage booking refunds and refund requests.',
                'category' => 'booking_management',
                'icon' => 'fas fa-rotate-left',
                'sort_order' => 50,
            ],

            // =====================================================
            // VENDOR MANAGEMENT
            // =====================================================

            [
                'name' => 'Vendor Management',
                'slug' => 'vendor-management',
                'key' => 'vendor_management',
                'description' => 'Manage marketplace vendors and vendor accounts.',
                'category' => 'vendor_management',
                'icon' => 'fas fa-store',
                'sort_order' => 60,
            ],

            [
                'name' => 'Vendor Commission',
                'slug' => 'vendor-commission',
                'key' => 'vendor_commission',
                'description' => 'Manage vendor commissions and commission rules.',
                'category' => 'vendor_management',
                'icon' => 'fas fa-percent',
                'sort_order' => 70,
            ],

            [
                'name' => 'Vendor Payouts',
                'slug' => 'vendor-payouts',
                'key' => 'vendor_payouts',
                'description' => 'Manage vendor payout processing.',
                'category' => 'vendor_management',
                'icon' => 'fas fa-money-bill-transfer',
                'sort_order' => 80,
            ],

            // =====================================================
            // RESORT & ROOM
            // =====================================================

            [
                'name' => 'Resort Management',
                'slug' => 'resort-management',
                'key' => 'resort_management',
                'description' => 'Manage resorts and accommodation listings.',
                'category' => 'resort_management',
                'icon' => 'fas fa-hotel',
                'sort_order' => 90,
            ],

            [
                'name' => 'Room Management',
                'slug' => 'room-management',
                'key' => 'room_management',
                'description' => 'Manage rooms, room types, prices and availability.',
                'category' => 'resort_management',
                'icon' => 'fas fa-bed',
                'sort_order' => 100,
            ],

            // =====================================================
            // TRANSPORT
            // =====================================================

            [
                'name' => 'Transport Management',
                'slug' => 'transport-management',
                'key' => 'transport_management',
                'description' => 'Manage transport vehicles and transport bookings.',
                'category' => 'transport',
                'icon' => 'fas fa-bus',
                'sort_order' => 110,
            ],

            // =====================================================
            // MARKETING
            // =====================================================

            [
                'name' => 'Coupon Management',
                'slug' => 'coupon-management',
                'key' => 'coupon_management',
                'description' => 'Create and manage promotional coupons.',
                'category' => 'marketing',
                'icon' => 'fas fa-ticket',
                'sort_order' => 120,
            ],

            [
                'name' => 'Advertisements',
                'slug' => 'advertisements',
                'key' => 'advertisements',
                'description' => 'Manage marketplace advertisements and promotional placements.',
                'category' => 'marketing',
                'icon' => 'fas fa-bullhorn',
                'sort_order' => 130,
            ],

            [
                'name' => 'Promotions',
                'slug' => 'promotions',
                'key' => 'promotions',
                'description' => 'Manage special promotions and promotional campaigns.',
                'category' => 'marketing',
                'icon' => 'fas fa-tags',
                'sort_order' => 140,
            ],

            // =====================================================
            // REPORTS & ANALYTICS
            // =====================================================

            [
                'name' => 'Basic Reports',
                'slug' => 'basic-reports',
                'key' => 'basic_reports',
                'description' => 'Access standard booking and revenue reports.',
                'category' => 'reports',
                'icon' => 'fas fa-chart-column',
                'sort_order' => 150,
            ],

            [
                'name' => 'Advanced Reports',
                'slug' => 'advanced-reports',
                'key' => 'advanced_reports',
                'description' => 'Access advanced business and marketplace reports.',
                'category' => 'reports',
                'icon' => 'fas fa-chart-line',
                'sort_order' => 160,
            ],

            [
                'name' => 'Advanced Analytics',
                'slug' => 'advanced-analytics',
                'key' => 'advanced_analytics',
                'description' => 'Access advanced marketplace analytics and insights.',
                'category' => 'reports',
                'icon' => 'fas fa-chart-pie',
                'sort_order' => 170,
            ],

            // =====================================================
            // API & INTEGRATIONS
            // =====================================================

            [
                'name' => 'API Access',
                'slug' => 'api-access',
                'key' => 'api_access',
                'description' => 'Allow access to the marketplace API.',
                'category' => 'integrations',
                'icon' => 'fas fa-code',
                'sort_order' => 180,
            ],

            [
                'name' => 'Webhooks',
                'slug' => 'webhooks',
                'key' => 'webhooks',
                'description' => 'Allow external webhook integrations.',
                'category' => 'integrations',
                'icon' => 'fas fa-link',
                'sort_order' => 190,
            ],

            // =====================================================
            // BRANDING
            // =====================================================

            [
                'name' => 'Custom Branding',
                'slug' => 'custom-branding',
                'key' => 'custom_branding',
                'description' => 'Allow custom branding and marketplace identity.',
                'category' => 'branding',
                'icon' => 'fas fa-palette',
                'sort_order' => 200,
            ],

            [
                'name' => 'White Label',
                'slug' => 'white-label',
                'key' => 'white_label',
                'description' => 'Allow white-label marketplace branding.',
                'category' => 'branding',
                'icon' => 'fas fa-certificate',
                'sort_order' => 210,
            ],

            // =====================================================
            // SUPPORT
            // =====================================================

            [
                'name' => 'Priority Support',
                'slug' => 'priority-support',
                'key' => 'priority_support',
                'description' => 'Provide priority customer support.',
                'category' => 'support',
                'icon' => 'fas fa-headset',
                'sort_order' => 220,
            ],

            // =====================================================
            // NOTIFICATIONS
            // =====================================================

            [
                'name' => 'Advanced Notifications',
                'slug' => 'advanced-notifications',
                'key' => 'advanced_notifications',
                'description' => 'Access advanced notification and communication features.',
                'category' => 'communication',
                'icon' => 'fas fa-bell',
                'sort_order' => 230,
            ],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                [
                    'key' => $feature['key'],
                ],
                array_merge(
                    $feature,
                    [
                        'is_active' => true,
                    ]
                )
            );
        }
    }
}