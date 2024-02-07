<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'firstName' => 'admin',
            'lastName' => 'admin',
            'email' => 'admin@admin.fr',
            'password' => bcrypt('admin'),
            'role' => 'admin',
            'userSlug' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'bio' => 'I am the admin of this website',
        ]);
        DB::table('users')->insert([
            'firstName' => 'user',
            'lastName' => 'user',
            'email' => 'user@user.fr',
            'password' => bcrypt('user'),
            'role' => 'user',
            'userSlug' => 'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'bio' => 'I am the user of this website',
        ]);

        // création de 6 utilisateurs aléatoires
        User::factory(6)->create();
    }
}
