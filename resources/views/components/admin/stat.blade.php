@props([
    'title',
    'value',
    'color' => 'gray',
    'href' => null,
])

@php
    $bg = [
        'green'  => 'bg-green-100 border-green-200 text-green-900',
        'yellow' => 'bg-yellow-100 border-yellow-200 text-yellow-900',
        'red'    => 'bg-red-100 border-red-200 text-red-900',
        'gray'   => 'bg-gray-100 border-gray-200 text-gray-900',
        'blue'   => 'bg-blue-100 border-blue-200 text-blue-900',
    ];

    $bgClass = $bg[$color] ?? $bg['gray'];

    $baseCard =
        'border rounded-xl p-6 shadow-sm ' .
        'transition hover:shadow-md hover:-translate-y-0.5 ' .
        'flex flex-col justify-between gap-4';
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $baseCard }} {{ $bgClass }} {{ $attributes->get('class') }}">
        <div>
            <div class="text-sm font-medium opacity-80">
                {{ $title }}
            </div>

            <div class="text-3xl font-semibold leading-none">
                {{ $value }}
            </div>
        </div>

        @isset($slot)
            <div class="text-sm font-medium">
                {{ $slot }}
            </div>
        @endisset
    </a>
@else
    <div class="{{ $baseCard }} {{ $bgClass }} {{ $attributes->get('class') }}">
        <div>
            <div class="text-sm font-medium opacity-80">
                {{ $title }}
            </div>

            <div class="text-3xl font-semibold leading-none">
                {{ $value }}
            </div>
        </div>

        @isset($slot)
            <div class="text-sm font-medium">
                {{ $slot }}
            </div>
        @endisset
    </div>
@endif
