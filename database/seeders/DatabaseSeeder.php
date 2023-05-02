<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Adding an admin user
        $user = \App\Models\User::factory()
            ->count(1)
            ->create([
                'email' => 'said10abderrahmen@gmail.com',
                'password' => \Hash::make('admin'),
            ]);
            $user = \App\Models\User::factory()
            ->count(1)
            ->create([
                'email' => 'minyarmeksi1@gmail.com',
                'password' => \Hash::make('1234'),
            ]);
            $user = \App\Models\User::factory()
            ->count(1)
            ->create([
                'email' => 'khdhiril@gmail.com',
                'password' => \Hash::make('1234'),
            ]);
        $this->call(PermissionsSeeder::class);

        $this->call(CourseSeeder::class);
        $this->call(NoteSeeder::class);
        $this->call(TestSeeder::class);
    }
}
