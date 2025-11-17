<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'student') {
            return $this->studentDashboard();
        } elseif ($user->role === 'employer') {
            return $this->employerDashboard();
        } else {
            return $this->adminDashboard();
        }
    }

    private function studentDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'applications' => Application::where('user_id', $user->id)->count(),
            'saved_jobs' => SavedJob::where('user_id', $user->id)->count(),
            'pending_applications' => Application::where('user_id', $user->id)->where('status', 'pending')->count(),
        ];

        $recentApplications = Application::with(['job.user'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $savedJobs = SavedJob::with(['job.user', 'job.category'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.student', compact('stats', 'recentApplications', 'savedJobs'));
    }

    private function employerDashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_jobs' => Job::where('user_id', $user->id)->count(),
            'active_jobs' => Job::where('user_id', $user->id)->where('status', 'active')->count(),
            'total_applications' => Application::whereHas('job', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'pending_applications' => Application::whereHas('job', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'pending')->count(),
        ];

        $recentJobs = Job::where('user_id', $user->id)
            ->withCount('applications')
            ->latest()
            ->limit(5)
            ->get();

        $recentApplications = Application::with(['user', 'job'])
            ->whereHas('job', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.employer', compact('stats', 'recentJobs', 'recentApplications'));
    }

    private function adminDashboard()
    {
        $stats = [
            'total_jobs' => Job::count(),
            'total_users' => \App\Models\User::count(),
            'total_companies' => \App\Models\User::where('role', 'employer')->count(),
            'total_applications' => Application::count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }
}