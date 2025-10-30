<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Công nghệ thông tin', 'icon' => 'fas fa-laptop-code'],
            ['name' => 'Nhà hàng - Khách sạn', 'icon' => 'fas fa-utensils'],
            ['name' => 'Bán hàng - Kinh doanh', 'icon' => 'fas fa-shopping-cart'],
            ['name' => 'Marketing - Quảng cáo', 'icon' => 'fas fa-bullhorn'],
            ['name' => 'Giáo dục - Đào tạo', 'icon' => 'fas fa-graduation-cap'],
            ['name' => 'Thiết kế - Sáng tạo', 'icon' => 'fas fa-palette'],
            ['name' => 'Dịch vụ khách hàng', 'icon' => 'fas fa-headset'],
            ['name' => 'Vận chuyển - Giao hàng', 'icon' => 'fas fa-truck'],
            ['name' => 'Sự kiện - Tổ chức', 'icon' => 'fas fa-calendar-alt'],
            ['name' => 'Làm đẹp - Chăm sóc', 'icon' => 'fas fa-spa'],
            ['name' => 'Gia sư - Dạy kèm', 'icon' => 'fas fa-chalkboard-teacher'],
            ['name' => 'Freelance - Tự do', 'icon' => 'fas fa-user-tie'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}