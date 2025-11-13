@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Cài đặt Hệ thống</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-lg shadow-md p-6">
            
            <div class="mb-4">
                <label for="app_name" class="block text-sm font-medium text-gray-700">Tên Website</label>
                <input type="text" name="app_name" id="app_name" 
                       value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="contact_email" class="block text-sm font-medium text-gray-700">Email Liên hệ</label>
                <input type="email" name="contact_email" id="contact_email" 
                       value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="app_logo" class="block text-sm font-medium text-gray-700">Logo Website</label>
                <input type="file" name="app_logo" id="app_logo" class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100">
                
                @if (isset($settings['app_logo']))
                    <img src="{{ Storage::url($settings['app_logo']) }}" alt="Logo" class="mt-4 h-16">
                @endif
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 border border-gray-300 text-gray-700 bg-indigo-600 rounded-md hover:bg-indigo-700">
                    Lưu Cài đặt
                </button>
            </div>
        </div>
    </form>
</div>
@endsection