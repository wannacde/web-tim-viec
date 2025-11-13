<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Hiển thị form cài đặt
     */
    public function index()
    {
        // Lấy tất cả cài đặt và chuyển thành dạng mảng [key => value]
        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Lưu hoặc cập nhật cài đặt
     */
    public function update(Request $request)
    {
        // Lấy tất cả dữ liệu, ngoại trừ token
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            // Xử lý nếu là file (ví dụ: logo)
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('logos', 'public');
                $value = $path;
            }

            // Dùng updateOrCreate để vừa tạo mới (nếu chưa có) vừa cập nhật (nếu đã có)
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Cài đặt đã được lưu.');
    }
}