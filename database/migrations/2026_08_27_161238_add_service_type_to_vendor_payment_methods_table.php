<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_payment_methods', function (Blueprint $table) {

            $table->enum('service_type', [
                'all',
                'resort',
                'transport',
            ])
                ->default('all')
                ->after('type');

        });
    }

    public function down(): void
    {
        Schema::table('vendor_payment_methods', function (Blueprint $table) {

            $table->dropColumn('service_type');

        });
    }
};