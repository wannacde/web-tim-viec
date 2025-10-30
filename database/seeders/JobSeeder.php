<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        
        $companyIds = DB::table('companies')->pluck('id')->toArray();
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        $locationIds = DB::table('locations')->pluck('id')->toArray();

        $jobTitles = [
            'Nhân viên bán hàng part-time',
            'Gia sư dạy kèm',
            'Nhân viên phục vụ',
            'Thiết kế đồ họa freelance',
            'Content writer',
            'Nhân viên giao hàng',
            'Hỗ trợ khách hàng online',
            'Nhân viên marketing',
            'Lập trình viên part-time',
            'Nhân viên sự kiện',
            'Dạy kèm tiếng Anh',
            'Nhân viên thu ngân',
            'Photographer part-time',
            'Nhân viên kho',
            'Social media manager',
        ];

        for ($i = 0; $i < 100; $i++) {
            $title = $faker->randomElement($jobTitles);
            $salaryMin = $faker->numberBetween(15, 50) * 1000;
            $salaryMax = $salaryMin + $faker->numberBetween(10, 30) * 1000;
            
            DB::table('jobs')->insert([
                'company_id' => $faker->randomElement($companyIds),
                'category_id' => $faker->randomElement($categoryIds),
                'location_id' => $faker->randomElement($locationIds),
                'title' => $title,
                'slug' => Str::slug($title . '-' . $i),
                'description' => $faker->text(1000),
                'requirements' => $faker->text(500),
                'benefits' => $faker->text(300),
                'salary_min' => $salaryMin,
                'salary_max' => $salaryMax,
                'salary_type' => $faker->randomElement(['hourly', 'daily', 'weekly', 'monthly']),
                'work_type' => $faker->randomElement(['online', 'offline', 'hybrid']),
                'work_schedule' => json_encode($faker->randomElements(['morning', 'afternoon', 'evening', 'weekend'], $faker->numberBetween(1, 3))),
                'experience_level' => $faker->randomElement(['no_experience', 'under_1_year', '1_3_years']),
                'positions' => $faker->numberBetween(1, 5),
                'status' => $faker->randomElement(['active', 'active', 'active', 'paused']), // 75% active
                'deadline' => $faker->dateTimeBetween('now', '+2 months'),
                'views' => $faker->numberBetween(0, 500),
                'is_featured' => $faker->boolean(20),
                'is_urgent' => $faker->boolean(15),
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}