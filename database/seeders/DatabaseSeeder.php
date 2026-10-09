<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin MAN 2 Bantul
        User::firstOrCreate(
            ['email' => 'admin@man2bantul.sch.id'],
            [
                'name' => 'Administrator MAN 2 Bantul',
                'password' => Hash::make('admin123'),
            ]
        );

        $this->call([
            PtkSeeder::class,
        ]);
    }
}
