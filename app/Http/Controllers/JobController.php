<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\SavedJob;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with(['user', 'category', 'location'])
            ->where('status', 'active')
            ->where('deadline', '>', now());

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location_id', $request->location);
        }

        // Filter by work type
        if ($request->filled('work_type')) {
            $query->where('work_type', $request->work_type);
        }

        // Filter by salary range
        if ($request->filled('salary_min')) {
            $query->where('salary_max', '>=', $request->salary_min);
        }
        if ($request->filled('salary_max')) {
            $query->where('salary_min', '<=', $request->salary_max);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'salary_high':
                $query->orderBy('salary_max', 'desc');
                break;
            case 'salary_low':
                $query->orderBy('salary_min', 'asc');
                break;
            case 'deadline':
                $query->orderBy('deadline', 'asc');
                break;
            default:
                $query->latest();
        }

        $jobs = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('jobs.index', compact('jobs', 'categories', 'locations'));
    }

    public function show(Job $job)
    {
        $job->increment('views');
        $job->load(['user', 'category', 'location']);
        
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

    // Employer job management methods
    public function employerIndex()
    {
        $employer = Auth::user();
        $jobs = Job::where('user_id', $employer->id)
            ->with(['category', 'location'])
            ->withCount('applications')
            ->latest()
            ->paginate(10);

        return view('dashboard.employer.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        
        return view('dashboard.employer.jobs.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'required|numeric|min:0|max:999999999',
            'salary_max' => 'required|numeric|gte:salary_min|max:999999999',
            'salary_type' => 'required|in:hourly,daily,weekly,monthly',
            'work_type' => 'required|in:online,offline,hybrid',
            'work_schedule' => 'required|array',
            'experience_level' => 'required|in:no_experience,under_1_year,1_3_years,over_3_years',
            'positions' => 'required|integer|min:1',
            'deadline' => 'required|date|after:today'
        ]);

        Job::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title . '-' . time()),
            'category_id' => $request->category_id,
            'location_id' => $request->location_id,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'benefits' => $request->benefits,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'salary_type' => $request->salary_type,
            'work_type' => $request->work_type,
            'work_schedule' => $request->work_schedule,
            'experience_level' => $request->experience_level,
            'positions' => $request->positions,
            'deadline' => $request->deadline,
            'status' => 'active'
        ]);

        return redirect()->route('employer.jobs.index')->with('success', 'Đăng tin thành công!');
    }

    public function edit(Job $job)
    {
        // Verify ownership
        if ($job->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        
        return view('dashboard.employer.jobs.edit', compact('job', 'categories', 'locations'));
    }

    public function update(Request $request, Job $job)
    {
        if (Auth::user()->role !== 'admin' && $job->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'required|numeric|min:0|max:999999999',
            'salary_max' => 'required|numeric|gte:salary_min|max:999999999',
            'salary_type' => 'required|in:hourly,daily,weekly,monthly',
            'work_type' => 'required|in:online,offline,hybrid',
            'work_schedule' => 'required|array',
            'experience_level' => 'required|in:no_experience,under_1_year,1_3_years,over_3_years',
            'positions' => 'required|integer|min:1',
            'deadline' => 'required|date|after_or_equal:today',
            'status' => 'required|in:draft,active,paused,expired,closed'
        ]);

        $job->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title . '-' . $job->id),
            'category_id' => $request->category_id,
            'location_id' => $request->location_id,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'benefits' => $request->benefits,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'salary_type' => $request->salary_type,
            'work_type' => $request->work_type,
            'work_schedule' => $request->work_schedule,
            'experience_level' => $request->experience_level,
            'positions' => $request->positions,
            'deadline' => $request->deadline,
            'status' => $request->status
        ]);

        return redirect()->route('employer.jobs.index')->with('success', 'Cập nhật tin thành công!');
    }

    public function destroy(Job $job)
    {
        if (Auth::user()->role !== 'admin' && $job->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $job->delete();
        return redirect()->route('employer.jobs.index')->with('success', 'Xóa tin thành công!');
    }
}ss', 'Xóa tin thành công!');
    }
}