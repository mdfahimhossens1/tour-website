<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->boolean('is_active')
                ->default(true)
                ->after('sort_order');

            $table->boolean('is_featured')
                ->default(false)
                ->after('is_active');

            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_featured']);

            $table->dropColumn([
                'is_active',
                'is_featured',
            ]);
        });
    }
};