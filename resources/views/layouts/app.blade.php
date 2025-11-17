<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-lg border-b border-gray-200/50" x-data="{ open: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-briefcase text-white text-lg"></i>
                            </div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                JobHub
                            </span>
                        </a>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">
                            <i class="fas fa-home mr-2"></i>Trang chủ
                        </a>
                        <a href="{{ route('jobs.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">
                            <i class="fas fa-search mr-2"></i>Tìm việc
                        </a>
                        
                        @auth
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </a>
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">
                                    Đăng nhập
                                </a>
                                <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                                    Đăng ký ngay
                                </a>
                            </div>
                        @endauth
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button @click="open = !open" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div x-show="open" x-transition class="md:hidden bg-white border-t border-gray-200">
                <div class="px-4 py-6 space-y-4">
                    <a href="{{ route('home') }}" class="block text-gray-700 hover:text-blue-600 font-medium">
                        <i class="fas fa-home mr-2"></i>Trang chủ
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-blue-600 font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left text-red-500 hover:text-red-700 font-medium">
                                <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block text-gray-700 hover:text-blue-600 font-medium">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold text-center">
                            Đăng ký ngay
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="pt-20">
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-briefcase text-white text-xl"></i>
                            </div>
                            <span class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                                JobHub
                            </span>
                        </div>
                        <p class="text-gray-300 mb-6 max-w-md">
                            Nền tảng tìm việc làm part-time hàng đầu dành cho sinh viên Việt Nam. 
                            Kết nối sinh viên với các cơ hội việc làm phù hợp.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Dành cho sinh viên</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li><a href="#" class="hover:text-white transition-colors">Tìm việc làm</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Tạo CV online</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Hướng dẫn phỏng vấn</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Blog nghề nghiệp</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Dành cho nhà tuyển dụng</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li><a href="#" class="hover:text-white transition-colors">Đăng tin tuyển dụng</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Tìm ứng viên</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Quản lý tuyển dụng</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Báo cáo thống kê</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                    <p>&copy; 2024 JobHub. Tất cả quyền được bảo lưu.</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>