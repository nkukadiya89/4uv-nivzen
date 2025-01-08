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
        $superAdminRole = Role::create(['name' => 'Administrator']); //as super-admin
        $adminRole = Role::create(['name' => 'golden-admin']);
        $staffRole = Role::create(['name' => 'super-admin']);
        $userRole = Role::create(['name' => 'dist-admin']);

        // Lets give all permission to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // Let's give few permissions to admin role.
        $adminRole->givePermissionTo(['create role', 'view role', 'update role']);
        $adminRole->givePermissionTo(['create permission', 'view permission']);
        $adminRole->givePermissionTo(['create user', 'view user', 'update user']);
        $adminRole->givePermissionTo(['create product', 'view product', 'update product']);


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

        $superAdminUser->assignRole($superAdminRole);


        $adminUser = User::firstOrCreate([
                            'email' => 'golden-admin@yopmail.com'
                        ], [
                            'name' => 'GoldenAdmin',
                            'firstname' => 'David',
                            'lastname' => 'Admin',
                            'dob' => now(),
                            'phone' => '9909923456',
                            'email' => 'golden-admin@yopmail.com',
                            'feature_access'=> '1',
                            'password' => Hash::make ('12345678'),
                        ]);

        $adminUser->assignRole($adminRole);


        $staffUser = User::firstOrCreate([
                            'email' => 'super-admin@yopmail.com',
                        ], [
                            'name' => 'SuperAdmin',
                            'firstname' => 'Maldi',
                            'lastname' => 'Staff',
                            'dob' => now(),
                            'phone' => '9723456078',
                            'email' => 'super-admin@yopmail.com',
                            'feature_access'=> '1',
                            'password' => Hash::make('12345678'),
                        ]);

        $staffUser->assignRole($staffRole);

        $distAdmin = User::firstOrCreate([
            'email' => 'dist-admin@yopmail.com',
        ], [
            'name' => 'DistAdmin',
            'firstname' => 'Maldi',
            'lastname' => 'Staff',
            'dob' => now(),
            'phone' => '9723456078',
            'email' => 'dist-admin@yopmail.com',
            'feature_access'=> '1',
            'password' => Hash::make('12345678'),
        ]);

        $distAdmin->assignRole($userRole);
    }
}
