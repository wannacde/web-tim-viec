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

        $recentApplications = Application::with(['job.company'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $savedJobs = SavedJob::with(['job.company', 'job.category'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.student', compact('stats', 'recentApplications', 'savedJobs'));
    }

    private function employerDashboard()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }

        $stats = [
            'total_jobs' => Job::where('company_id', $company->id)->count(),
            'active_jobs' => Job::where('company_id', $company->id)->where('status', 'active')->count(),
            'total_applications' => Application::whereHas('job', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->count(),
            'pending_applications' => Application::whereHas('job', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->where('status', 'pending')->count(),
        ];

        $recentJobs = Job::where('company_id', $company->id)
            ->withCount('applications')
            ->latest()
            ->limit(5)
            ->get();

        $recentApplications = Application::with(['user', 'job'])
            ->whereHas('job', function($q) use ($company) {
                $q->where('company_id', $company->id);
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
            'total_companies' => \App\Models\Company::count(),
            'total_applications' => Application::count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }
}