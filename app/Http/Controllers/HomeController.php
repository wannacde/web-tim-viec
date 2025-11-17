<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with(['user', 'category', 'location'])
            ->where('status', 'active')
            ->where('deadline', '>=', now());

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by location
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter by salary
        if ($request->filled('salary_min')) {
            $query->where('salary_max', '>=', $request->salary_min);
        }

        $jobs = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->get();
        $locations = Location::where('type', 'province')->where('is_active', true)->get();
        $featuredJobs = Job::with(['user', 'category', 'location'])
            ->where('is_featured', true)
            ->where('status', 'active')
            ->limit(6)
            ->get();

        return view('home', compact('jobs', 'categories', 'locations', 'featuredJobs'));
    }
}