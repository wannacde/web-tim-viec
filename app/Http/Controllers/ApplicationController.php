<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use App\Notifications\NewApplicationReceived;
use App\Notifications\ApplicationStatusUpdated;
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

        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => Auth::id(),
            'cv_file' => $cvPath,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending'
        ]);

        // Gửi thông báo cho nhà tuyển dụng
        $job->user->notify(new NewApplicationReceived($application));
        
        // Broadcast real-time notification
        $notificationData = [
            'id' => uniqid(),
            'type' => 'NewApplicationReceived',
            'data' => [
                'message' => "Bạn có đơn ứng tuyển mới cho công việc '{$job->title}' từ " . Auth::user()->name,
                'application_id' => $application->id,
                'job_id' => $job->id,
                'student_name' => Auth::user()->name,
                'job_title' => $job->title,
                'url' => route('employer.applicants')
            ],
            'created_at' => now()->toISOString(),
            'created_at_human' => 'Vừa xong'
        ];
        broadcast(new \App\Events\NotificationSent($job->user_id, $notificationData));

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

        $oldStatus = $application->status;
        
        $application->update([
            'status' => $request->status,
            'reviewed_at' => now()
        ]);

        // Gửi thông báo cho sinh viên nếu trạng thái thay đổi
        if ($oldStatus !== $request->status) {
            $application->user->notify(new ApplicationStatusUpdated($application));
            
            // Broadcast real-time notification
            $statusMap = [
                'pending' => 'Đang chờ duyệt',
                'reviewing' => 'Đang xem xét', 
                'accepted' => 'Đã chấp nhận',
                'rejected' => 'Đã từ chối'
            ];
            $statusText = $statusMap[$request->status] ?? $request->status;
            
            $notificationData = [
                'id' => uniqid(),
                'type' => 'ApplicationStatusUpdated',
                'data' => [
                    'message' => "Trạng thái đơn ứng tuyển cho công việc '{$application->job->title}' đã chuyển thành: {$statusText}",
                    'application_id' => $application->id,
                    'job_id' => $application->job_id,
                    'job_title' => $application->job->title,
                    'status' => $request->status,
                    'status_text' => $statusText,
                    'url' => route('student.applications')
                ],
                'created_at' => now()->toISOString(),
                'created_at_human' => 'Vừa xong'
            ];
            broadcast(new \App\Events\NotificationSent($application->user_id, $notificationData));
        }

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    /**
     * Hiển thị danh sách các đơn ứng tuyển của sinh viên.
     */
    public function studentApplications()
    {
        $applications = Application::with(['job.user', 'job.category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('dashboard.student.applications', compact('applications'));
    }


}