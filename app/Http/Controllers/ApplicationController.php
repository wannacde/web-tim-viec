<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function store(Request $request, Job $job)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'required|string|max:2000'
        ]);

        // Check if already applied
        $existingApplication = Application::where('job_id', $job->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingApplication) {
            return back()->with('error', 'Bạn đã ứng tuyển công việc này rồi!');
        }

        // Store CV file
        $cvPath = $request->file('cv')->store('cvs', 'public');

        Application::create([
            'job_id' => $job->id,
            'user_id' => Auth::id(),
            'cv_file' => $cvPath,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Ứng tuyển thành công!');
    }

    public function listApplicants()
    {
        $employer = Auth::user();
        
        $applications = Application::with(['user', 'job'])
            ->whereHas('job', function($query) use ($employer) {
                $query->where('user_id', $employer->id);
            })
            ->latest()
            ->paginate(20);

        return view('dashboard.employer.applicants', compact('applications'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,interviewed,accepted,rejected'
        ]);

        // Verify employer owns this application's job
        if ($application->job->user_id !== Auth::id()) {
            abort(403);
        }

        $application->update([
            'status' => $request->status,
            'reviewed_at' => now()
        ]);

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    /**
     * Hiển thị danh sách các đơn ứng tuyển của sinh viên.
     */
    public function studentApplications()
    {
        $applications = Application::with(['job.company', 'job.category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('dashboard.student.applications', compact('applications'));
    }
}