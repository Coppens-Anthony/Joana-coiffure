<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Service;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Client::factory(10)->create();

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => Hash::make('password'),
        ]);

        Service::factory(10)->create();
    }
}
