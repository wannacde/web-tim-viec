<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Hiển thị trang báo cáo chính
     */
    public function index()
    {
        // Thống kê người dùng đăng ký 30 ngày qua
        $usersPerDay = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('count', 'date');

        // Thống kê tin đăng 30 ngày qua
        $jobsPerDay = Job::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('count', 'date');

        // Thống kê việc làm theo danh mục
        $jobsByCategory = Category::withCount('jobs')
            ->pluck('jobs_count', 'name');

        return view('admin.reports.index', compact('usersPerDay', 'jobsPerDay', 'jobsByCategory'));
    }
}