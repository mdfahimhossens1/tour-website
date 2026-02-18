<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    // Roles ensure
        $superadmin   = Role::firstOrCreate(['role_name' => 'super admin']);
        $admin   = Role::firstOrCreate(['role_name' => 'admin']);
        $manager = Role::firstOrCreate(['role_name' => 'manager']);
        $user  = Role::firstOrCreate(['role_name' => 'user']);

        // Main admin ensure (match by email)
        User::updateOrCreate(
            ['email' => 'mdfahimhossen629@gmail.com'],   // <-- where clause
            [
                'name'     => 'Fahim',
                'username' => 'Main Admin',
                'password' => Hash::make('Admin@123456'),
                'slug' => Str::slug('main-admin'),
                'role_id'  => $superadmin->id,
            ]
        );
    }
}
