<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminExists = User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['admin', 'super-admin']))
            ->exists();

        // Idempotent + safe on every deploy: once any admin exists we leave it
        // alone, so a password changed in the panel is never overwritten. The
        // seeded password is only ever set on first creation.
        if ($adminExists) {
            return;
        }

        User::firstOrCreate(
            ['email' => config('admin.email')],
            [
                'name' => config('admin.name'),
                'password' => Hash::make(config('admin.password')),
                'email_verified_at' => now(),
            ]
        )->syncRoles(['admin', 'super-admin']);
    }
}
