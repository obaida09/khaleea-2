<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
        ]);

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage-categories',
            'view-categories',
            'edit-categories',
            'create-categories',
            'delete-categories',

            'manage-tags',
            'view-tags',
            'edit-tags',
            'create-tags',
            'delete-tags',

            'manage-products',
            'view-products',
            'edit-products',
            'create-products',
            'delete-products',

            'manage-coupons',
            'view-coupons',
            'edit-coupons',
            'create-coupons',
            'delete-coupons',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign existing permissions
        $helper = Role::create(['name' => 'helper']);
        $helper->givePermissionTo([
            'manage-categories',
            'view-categories',
            'edit-categories',
            'create-categories',
            'delete-categories',
            'manage-tags',
            'view-tags',
            'edit-tags',
            'create-tags',
            'delete-tags',
        ]);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $user = User::factory()->create([
            'name' => 'Example Admin User',
            'email' => 'admin@example.com',
            'mobile' => '07724389401',
            'password' => Hash::make('obeda2001'),
        ]);

        $user->assignRole($admin);
    }
}
