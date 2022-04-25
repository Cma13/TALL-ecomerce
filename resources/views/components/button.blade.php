@props(['color' => '$color'])

<button {{ $attributes->merge(['type' => 'submit', 'class' => "justify-center inline-flex items-center px-4 py-2 bg-$color-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-$color-600 active:bg-$color-500 focus:outline-none focus:border-$color-500 focus:ring focus:ring-$color-300 disabled:opacity-25 transition"]) }}>
    {{ $slot }}
</button>