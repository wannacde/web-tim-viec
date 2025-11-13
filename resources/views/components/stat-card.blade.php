@props(['title', 'value', 'icon', 'color' => 'blue'])

@php
$colorClasses = [
    'blue' => 'bg-blue-100 text-blue-600',
    'green' => 'bg-green-100 text-green-600',
    'yellow' => 'bg-yellow-100 text-yellow-600',
    'purple' => 'bg-purple-100 text-purple-600',
    'red' => 'bg-red-100 text-red-600',
    'orange' => 'bg-orange-100 text-orange-600',
];
@endphp

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center">
        <div class="p-3 rounded-full {{ $colorClasses[$color] ?? $colorClasses['blue'] }}">
            <i class="{{ $icon }} text-xl"></i>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $value }}</p>
        </div>
    </div>
</div>