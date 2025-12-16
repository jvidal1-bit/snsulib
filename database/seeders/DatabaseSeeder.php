<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
        ['email' => 'student@snsu.test'],
        [
            'name'     => 'student',           // this is the username
            'password' => Hash::make('password'),
            'role'     => 'student',           // you already added 'role' to users
        ]
    );
    }
}
