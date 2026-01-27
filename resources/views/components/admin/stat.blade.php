@php
    $colors = [
        'green'  => 'text-green-700 bg-green-50',
        'yellow' => 'text-yellow-700 bg-yellow-50',
        'red'    => 'text-red-700 bg-red-50',
        'gray'   => 'text-gray-700 bg-gray-50',
        'blue'   => 'text-blue-700 bg-blue-50',
    ];
@endphp

<div class="border rounded-lg p-5 {{ $colors[$color] ?? $colors['gray'] }}">
    <div class="text-sm font-medium text-gray-500 mb-1">
        {{ $title }}
    </div>
    <div class="text-2xl font-semibold">
        {{ $value }}
    </div>
</div>