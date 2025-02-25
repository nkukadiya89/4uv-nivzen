<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the  database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'update permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'view product']);
        Permission::create(['name' => 'create product']);
        Permission::create(['name' => 'update product']);
        Permission::create(['name' => 'delete product']);


        // Create Roles
        $administratorRole = Role::create(['name' => 'Administrator']); //as super-admin
        $goldAdminRole = Role::create(['name' => 'GoldenAdmin']);
        $superAdminRole = Role::create(['name' => 'SuperAdmin']);
        $distAdminRole = Role::create(['name' => 'DistAdmin']);

        // Lets give all permission to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $administratorRole->givePermissionTo($allPermissionNames);

        // Let's give few permissions to admin role.
        $goldAdminRole->givePermissionTo(['create role', 'view role', 'update role']);
        $goldAdminRole->givePermissionTo(['create permission', 'view permission']);
        $goldAdminRole->givePermissionTo(['create user', 'view user', 'update user']);
        $goldAdminRole->givePermissionTo(['create product', 'view product', 'update product']);


        // Let's Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
                    'email' => 'administrator@yopmail.com',
                ], [
                    'name' => 'Administrator',
                    'firstname' => 'Super',
                    'lastname' => 'Admin',
                    'dob' => now(),
                    'phone' => '9875024235',
                    'email' => 'administrator@yopmail.com',
                    'feature_access'=> '1',
                    'password' => Hash::make ('12345678'),
                ]);

        $superAdminUser->assignRole($administratorRole);


        $goldenAdmin = User::firstOrCreate([
                            'email' => 'golden-admin@yopmail.com'
                        ], [
                            'name' => 'GoldenAdmin',
                            'firstname' => 'Maulik',
                            'lastname' => 'Bhavsar',
                            'dob' => now(),
                            'phone' => '9909923456',
                            'email' => 'maulik.bhavsar@yopmail.com',
                            'feature_access'=> '1',
                            'password' => Hash::make ('12345678'),
                            'upline_id' => $superAdminUser->id
                        ]);

        $goldenAdmin->assignRole($goldAdminRole);


        $superAdmin1 = User::firstOrCreate([
                            'email' => 'super-admin@yopmail.com',
                        ], [
                            'name' => 'UmangAdmin',
                            'firstname' => 'Umang',
                            'lastname' => 'admin',
                            'dob' => now(),
                            'phone' => '9723456078',
                            'email' => 'umang-admin@yopmail.com',
                            'feature_access'=> '1',
                            'password' => Hash::make('12345678'),
                             'upline_id' => $goldenAdmin->id
                        ]);

        $superAdmin1->assignRole($superAdminRole);

        $distAdmin1 = User::firstOrCreate([
            'email' => 'dist-admin@yopmail.com',
        ], [
            'name' => 'Bapu',
            'firstname' => 'Bapu',
            'lastname' => 'Staff',
            'dob' => now(),
            'phone' => '9723456078',
            'email' => 'bapu@yopmail.com',
            'feature_access'=> '1',
            'password' => Hash::make('12345678'),
            'upline_id' => $superAdmin1->id
        ]);

        $distAdmin1->assignRole($distAdminRole);
    }
}
