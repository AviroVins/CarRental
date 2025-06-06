<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;
use Database\Factories\Providers\PeselProvider;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        User::create([
            'pesel' => '00000000000',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@car.com',
            'password' => Hash::make('admin123'),
            'phone_number' => '123456789',
            'has_driver_license' => true,
            'account_status' => 'active',
            'role' => 'admin',
        ]);

        User::create([
            'pesel' => '00011122233',
            'first_name' => 'Mateusz',
            'last_name' => 'Test',
            'email' => 'test@car.com',
            'password' => Hash::make('mateusz123'),
            'phone_number' => '123456788',
            'has_driver_license' => true,
            'account_status' => 'active',
            'role' => 'user',
        ]);

        $faker->addProvider(new PeselProvider($faker));
        
        foreach (range(1, 10) as $i) {
            User::create([
                'pesel' => $faker->unique()->pesel(),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'phone_number' => $faker->phoneNumber,
                'has_driver_license' => $faker->boolean,
                'account_status' => $faker->randomElement(['active', 'inactive', 'blocked']),
                'role' => 'user',
            ]);
        }
    }
}