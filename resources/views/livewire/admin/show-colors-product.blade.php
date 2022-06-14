<div class="flex">
    @if ($products->count())
        @foreach ($products as $product)
            <div class="mr-1">
                <x-color-circle color="{{ $product->color->name }}" />
            </div>
        @endforeach
    @else
        Sin colores aún
    @endif
</div>
