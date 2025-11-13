<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job:slug}', [JobController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{job}/save', [JobController::class, 'save'])->name('jobs.save');

// Company routes
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company:slug}', [CompanyController::class, 'show'])->name('companies.show');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Application routes
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('jobs.apply');
    
    // Employer routes
    Route::middleware('role:employer')->group(function () {
        Route::get('/dashboard/employer/applicants', [ApplicationController::class, 'listApplicants'])->name('employer.applicants');
        Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
        
        // Job management
        Route::get('/dashboard/employer/jobs', [JobController::class, 'employerIndex'])->name('employer.jobs.index');
        Route::get('/dashboard/employer/jobs/create', [JobController::class, 'create'])->name('employer.jobs.create');
        Route::post('/dashboard/employer/jobs', [JobController::class, 'store'])->name('employer.jobs.store');
        Route::get('/dashboard/employer/jobs/{job}/edit', [JobController::class, 'edit'])->name('employer.jobs.edit');
        Route::put('/dashboard/employer/jobs/{job}', [JobController::class, 'update'])->name('employer.jobs.update');
        Route::delete('/dashboard/employer/jobs/{job}', [JobController::class, 'destroy'])->name('employer.jobs.destroy');
        
        // Company management
        Route::get('/dashboard/employer/company/edit', [CompanyController::class, 'edit'])->name('company.edit');
        Route::put('/dashboard/employer/company', [CompanyController::class, 'update'])->name('company.update');
    });
    
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // User management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        
        // Category management
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Job management
        Route::get('/jobs', [AdminJobController::class, 'index'])->name('jobs.index');
        Route::delete('/jobs/{job}', [AdminJobController::class, 'destroy'])->name('jobs.destroy');
    });
});

require __DIR__.'/auth.php';
