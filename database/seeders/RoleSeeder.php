<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::updateOrCreate(
            ['role_name' => 'super_admin']
        );

        $admin = Role::updateOrCreate(
            ['role_name' => 'admin']
        );

        $manager = Role::updateOrCreate(
            ['role_name' => 'manager']
        );

        $vendor = Role::updateOrCreate(
            ['role_name' => 'vendor']
        );

        $user = Role::updateOrCreate(
            ['role_name' => 'user']
        );

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN USER
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            [
                'email' => 'superadmin@demo.com',
            ],
            [
                'name' => 'Super Admin',

                'username' => 'superadmin',

                'password' =>
                    Hash::make('password'),

                'slug' =>
                    Str::slug('super-admin'),

                'role_id' =>
                    $superAdmin->id,

                'status' => 1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ADMIN USER
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            [
                'email' => 'admin@demo.com',
            ],
            [
                'name' => 'Admin User',

                'username' => 'admin',

                'password' =>
                    Hash::make('password'),

                'slug' =>
                    Str::slug('admin-user'),

                'role_id' =>
                    $admin->id,

                'status' => 1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | MANAGER USER
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            [
                'email' => 'manager@demo.com',
            ],
            [
                'name' => 'Manager User',

                'username' => 'manager',

                'password' =>
                    Hash::make('password'),

                'slug' =>
                    Str::slug('manager-user'),

                'role_id' =>
                    $manager->id,

                'status' => 1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VENDOR USER
        |--------------------------------------------------------------------------
        */

        $vendorUser = User::updateOrCreate(
            [
                'email' => 'vendor@demo.com',
            ],
            [
                'name' => 'Vendor User',

                'username' => 'vendor',

                'password' =>
                    Hash::make('password'),

                'slug' =>
                    Str::slug('vendor-user'),

                'role_id' =>
                    $vendor->id,

                /*
                | Vendor is approved in demo seed
                */
                'status' => 1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VENDOR PROFILE
        |--------------------------------------------------------------------------
        |
        | Vendor status:
        |
        | pending
        | approved
        | rejected
        |
        |--------------------------------------------------------------------------
        */

        Vendor::updateOrCreate(
            [
                'user_id' => $vendorUser->id,
            ],
            [
                'business_name' =>
                    'Demo Travel Shop',

                'phone' =>
                    '01700000000',

                'address' =>
                    'Khulna',

                'status' =>
                    'approved',

                'commission_rate' =>
                    10,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | NORMAL USER
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            [
                'email' => 'user@demo.com',
            ],
            [
                'name' => 'Normal User',

                'username' => 'user',

                'password' =>
                    Hash::make('password'),

                'slug' =>
                    Str::slug('normal-user'),

                'role_id' =>
                    $user->id,

                'status' => 1,
            ]
        );
    }
}
