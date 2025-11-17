<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Admin user
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@webtimviec.com',
            'phone' => '0123456789',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Employer users
        for ($i = 1; $i <= 10; $i++) {
            $companyName = $faker->company;
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => "employer{$i}@example.com",
                'phone' => $faker->phoneNumber,
                'role' => 'employer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'bio' => $faker->text(200),
                'company_name' => $companyName,
                'company_description' => $faker->text(500),
                'company_website' => $faker->url,
                'company_address' => $faker->address,
                'is_verified' => $faker->boolean(80),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Student users
        for ($i = 1; $i <= 50; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => "student{$i}@example.com",
                'phone' => $faker->phoneNumber,
                'role' => 'student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'bio' => $faker->text(200),
                'date_of_birth' => $faker->dateTimeBetween('-25 years', '-18 years'),
                'gender' => $faker->randomElement(['male', 'female']),
                'address' => $faker->address,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}