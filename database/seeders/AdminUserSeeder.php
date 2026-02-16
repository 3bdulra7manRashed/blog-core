<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Use config values (cache-safe) with hardcoded defaults for the main admin
        $name = config('app.admin_name', 'Saleh Alshehri');
        $email = config('app.admin_email', 'admin@alshehri.com');
        $password = config('app.admin_password', 'password');

        // Ensure admin role exists
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Check if user already exists to preserve profile_photo_path
        $existingUser = User::where('email', $email)->first();

        $userData = [
            'name' => $name,
            'password' => Hash::make($password),
            'is_admin' => true,
            'is_super_admin' => true,
            'role' => UserRole::ADMIN,
            'email_verified_at' => now(),
        ];

        // Only set profile_photo_path if user doesn't exist (preserve existing photo)
        if (!$existingUser) {
            $userData['profile_photo_path'] = null;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            $userData
        );

        // Assign Spatie admin role if not already assigned
        if (!$user->hasRole('admin')) {
            $user->assignRole($role);
        }

        // Ensure the Site Owner setting points to this admin user (Safe — skip if settings table missing)
        if (Schema::hasTable('settings')) {
            \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
                ['key' => 'site_owner_user_id'],
                ['value' => $user->id]
            );
        }

        $this->command->info("✓ Admin user '{$name}' (ID: {$user->id}) created/updated with Role: Admin, Super Admin: Yes, Site Owner: Sync");
    }
}