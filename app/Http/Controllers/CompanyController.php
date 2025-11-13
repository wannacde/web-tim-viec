<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::with(['location', 'user'])
            ->withCount('jobs')
            ->where('is_verified', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $companies = $query->latest()->paginate(12);
        $locations = Location::where('type', 'province')->where('is_active', true)->get();

        return view('companies.index', compact('companies', 'locations'));
    }

    public function show(Company $company)
    {
        $company->load(['location', 'user']);
        
        $jobs = $company->jobs()
            ->where('status', 'active')
            ->where('deadline', '>=', now())
            ->with(['category', 'location'])
            ->latest()
            ->paginate(6);

        return view('companies.show', compact('company', 'jobs'));
    }
    public function edit()
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            abort(404, 'Company not found');
        }

        $locations = Location::where('is_active', true)->get();
        
        return view('dashboard.employer.company.edit', compact('company', 'locations'));
    }

    public function update(Request $request)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            abort(404, 'Company not found');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'location_id' => 'nullable|exists:locations,id',
            'size' => 'nullable|in:1-10,11-50,51-200,201-500,500+',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . $company->id),
            'description' => $request->description,
            'website' => $request->website,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'location_id' => $request->location_id,
            'size' => $request->size,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);

        return redirect()->route('company.edit')->with('success', 'Cập nhật thông tin công ty thành công!');
    }
}