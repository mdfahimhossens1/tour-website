<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE ads
            MODIFY position ENUM(
                'home_top',
                'home_middle',
                'packages_top',
                'tour_details',
                'blog'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE ads
            MODIFY position ENUM(
                'homepage_banner',
                'sidebar',
                'package_page',
                'footer'
            ) NOT NULL
        ");
    }
};