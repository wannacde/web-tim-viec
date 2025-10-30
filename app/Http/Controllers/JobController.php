<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function show(Job $job)
    {
        $job->increment('views');
        $job->load(['company', 'category', 'location']);
        
        $relatedJobs = Job::where('category_id', $job->category_id)
            ->where('id', '!=', $job->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        $isSaved = false;
        if (Auth::check()) {
            $isSaved = SavedJob::where('user_id', Auth::id())
                ->where('job_id', $job->id)
                ->exists();
        }

        return view('jobs.show', compact('job', 'relatedJobs', 'isSaved'));
    }

    public function save(Job $job)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $saved = SavedJob::where('user_id', Auth::id())
            ->where('job_id', $job->id)
            ->first();

        if ($saved) {
            $saved->delete();
            return response()->json(['saved' => false, 'message' => 'Đã bỏ lưu công việc']);
        } else {
            SavedJob::create([
                'user_id' => Auth::id(),
                'job_id' => $job->id
            ]);
            return response()->json(['saved' => true, 'message' => 'Đã lưu công việc']);
        }
    }
}