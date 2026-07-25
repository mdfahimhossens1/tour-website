<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Policy;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policies = [

            [
                'type' => 'privacy',
                'title_en' => 'Privacy Policy',
                'title_bn' => 'গোপনীয়তা নীতি',
                'content_en' => '',
                'content_bn' => '',
                'status' => true,
            ],

            [
                'type' => 'terms',
                'title_en' => 'Terms & Conditions',
                'title_bn' => 'শর্তাবলী',
                'content_en' => '',
                'content_bn' => '',
                'status' => true,
            ],

            [
                'type' => 'refund',
                'title_en' => 'Refund Policy',
                'title_bn' => 'ফেরত নীতি',
                'content_en' => '',
                'content_bn' => '',
                'status' => true,
            ],

            [
                'type' => 'cookies',
                'title_en' => 'Cookie Policy',
                'title_bn' => 'কুকি নীতি',
                'content_en' => '',
                'content_bn' => '',
                'status' => true,
            ],

        ];

        foreach ($policies as $policy) {

            Policy::updateOrCreate(

                [
                    'type' => $policy['type']
                ],

                $policy

            );

        }
    }
}