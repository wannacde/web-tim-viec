@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Chỉnh sửa Profile</h1>
            <p class="text-gray-600 mt-2">Cập nhật thông tin cá nhân và cài đặt tài khoản của bạn</p>
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            @if(Auth::user()->role === 'student')
            <div class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.update-student-profile-form')
            </div>
            @endif

            @if(Auth::user()->role === 'employer')
            <div class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.update-company-information-form')
            </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
