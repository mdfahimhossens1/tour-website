<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {

            $table->foreignId('tour_type_id')
                ->nullable()
                ->after('destination_id')
                ->constrained()
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {

            $table->dropForeign(['tour_type_id']);

            $table->dropColumn('tour_type_id');

        });
    }
};