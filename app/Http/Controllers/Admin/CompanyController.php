<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Hiển thị danh sách tất cả công ty
     */
    public function index()
    {
        $companies = Company::with('user')->latest()->paginate(20);
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Hiển thị form chỉnh sửa công ty
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Cập nhật thông tin công ty
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_verified' => 'sometimes|boolean',
        ]);

        $company->update([
            'name' => $request->name,
            'is_verified' => $request->has('is_verified'),
        ]);

        return redirect()->route('admin.companies.index')->with('success', 'Cập nhật công ty thành công.');
    }

    /**
     * Xóa công ty
     */
    public function destroy(Company $company)
    {
        // Tùy chọn: Xóa cả các job liên quan (sẽ thêm logic sau)
        // $company->jobs()->delete();
        
        $company->delete();
        return back()->with('success', 'Đã xóa công ty.');
    }
}