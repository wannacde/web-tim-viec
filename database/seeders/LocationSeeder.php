<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Tỉnh/Thành phố
        $provinces = [
            'Hà Nội', 'TP. Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ',
            'Bình Dương', 'Đồng Nai', 'Khánh Hòa', 'Lâm Đồng', 'Quảng Nam',
            'Bà Rịa - Vũng Tàu', 'Thừa Thiên Huế', 'Kiên Giang', 'Bắc Ninh', 'Quảng Ninh'
        ];

        $provinceIds = [];
        foreach ($provinces as $province) {
            $id = DB::table('locations')->insertGetId([
                'name' => $province,
                'slug' => Str::slug($province),
                'type' => 'province',
                'parent_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $provinceIds[$province] = $id;
        }

        // Quận/Huyện cho Hà Nội
        $hanoiDistricts = [
            'Ba Đình', 'Hoàn Kiếm', 'Tây Hồ', 'Long Biên', 'Cầu Giấy',
            'Đống Đa', 'Hai Bà Trưng', 'Hoàng Mai', 'Thanh Xuân', 'Nam Từ Liêm',
            'Bắc Từ Liêm', 'Hà Đông'
        ];

        foreach ($hanoiDistricts as $district) {
            DB::table('locations')->insert([
                'name' => $district,
                'slug' => Str::slug($district),
                'type' => 'district',
                'parent_id' => $provinceIds['Hà Nội'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Quận/Huyện cho TP.HCM
        $hcmDistricts = [
            'Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5',
            'Quận 6', 'Quận 7', 'Quận 8', 'Quận 9', 'Quận 10',
            'Quận 11', 'Quận 12', 'Thủ Đức', 'Bình Thạnh', 'Gò Vấp',
            'Phú Nhuận', 'Tân Bình', 'Tân Phú'
        ];

        foreach ($hcmDistricts as $district) {
            DB::table('locations')->insert([
                'name' => $district,
                'slug' => Str::slug($district),
                'type' => 'district',
                'parent_id' => $provinceIds['TP. Hồ Chí Minh'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}