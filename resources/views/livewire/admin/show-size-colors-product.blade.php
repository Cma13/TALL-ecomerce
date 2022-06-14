<div class="flex">
    @if ($colors)
        @foreach ($colors as $color)
            <div class="mr-1">
                <x-color-circle color="{{ $color }}" />
            </div>
        @endforeach
    @else
        Sin colores aún
    @endif
</div>
