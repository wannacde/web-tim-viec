@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Báo cáo Thống kê</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Người dùng mới (30 ngày qua)</h2>
            <canvas id="usersChart"></canvas>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Tin đăng mới (30 ngày qua)</h2>
            <canvas id="jobsChart"></canvas>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 col-span-1 lg:col-span-2">
            <h2 class="text-xl font-semibold mb-4">Tỉ lệ Việc làm theo Danh mục</h2>
            <div class="max-w-lg mx-auto">
                <canvas id="categoriesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Dữ liệu từ Controller
    const usersData = @json($usersPerDay ?? []);
    const jobsData = @json($jobsPerDay ?? []);
    const categoriesData = @json($jobsByCategory ?? []);

    // Biểu đồ 1: Người dùng
    if (document.getElementById('usersChart')) {
        new Chart(document.getElementById('usersChart'), {
            type: 'line',
            data: {
                labels: Object.keys(usersData),
                datasets: [{
                    label: 'Người dùng mới',
                    data: Object.values(usersData),
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                }]
            }
        });
    }

    // Biểu đồ 2: Tin đăng
    if (document.getElementById('jobsChart')) {
        new Chart(document.getElementById('jobsChart'), {
            type: 'line',
            data: {
                labels: Object.keys(jobsData),
                datasets: [{
                    label: 'Tin đăng mới',
                    data: Object.values(jobsData),
                    borderColor: 'rgb(34, 197, 94)',
                    tension: 0.1
                }]
            }
        });
    }

    // Biểu đồ 3: Danh mục
    if (document.getElementById('categoriesChart')) {
        new Chart(document.getElementById('categoriesChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(categoriesData),
                datasets: [{
                    label: 'Số lượng việc làm',
                    data: Object.values(categoriesData),
                    backgroundColor: [
                        'rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)', 'rgb(153, 102, 255)', 'rgb(255, 159, 64)',
                        'rgb(231, 235, 96)', 'rgb(96, 235, 148)', 'rgb(235, 96, 178)'
                    ],
                    hoverOffset: 4
                }]
            }
        });
    }
</script>
@endsection