@props(['color' => 'green'])

<?php
if ($color == 'white' || $color == 'black') {
    $bgColor = "bg-$color";
} else {
    $bgColor = "bg-$color-600";
}
?>

<div {{ $attributes->merge(['class' => "h-5 w-5 rounded-full border-2 border-gray-300 $bgColor"]) }}></div>
{{-- @switch($color)
    @case('white')
        <div {{ $attributes->merge(['class' => 'h-5 w-5 rounded-full border-2 border-gray-300 bg-white']) }}></div>
    @break

    @case('blue')
        <div {{ $attributes->merge(['class' => 'h-5 w-5 rounded-full border-2 border-gray-300 bg-blue-500']) }}></div>
    @break

    @case('red')
        <div {{ $attributes->merge(['class' => 'h-5 w-5 rounded-full border-2 border-gray-300 bg-red-500']) }}></div>
    @break

    @case('black')
        <div {{ $attributes->merge(['class' => 'h-5 w-5 rounded-full border-2 border-gray-300 bg-black']) }}></div>
    @break

    @default --}}
{{-- @endswitch --}}
