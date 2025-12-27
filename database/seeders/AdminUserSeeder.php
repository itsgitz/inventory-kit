<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use function Illuminate\Support\now;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => env('ADMIN_EMAIL'),
        ], [
            'name' => env('ADMIN_NAME'),
            'username' => env('ADMIN_USERNAME'),
            'email_verified_at' => now(),
            'password' => Hash::make(env('ADMIN_PASSWORD')),
        ]);
    }
}
