<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{

    Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name')->unique(); // admin, manager, user
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['role_name' => 'Super Admin', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'User', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
