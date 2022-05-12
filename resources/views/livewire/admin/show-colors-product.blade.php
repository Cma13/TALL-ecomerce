<div class="flex">
    @foreach ($products as $product)
        <div class="mr-1">
            <x-color-circle color="{{ $product->color->name }}" />
        </div>
    @endforeach
</div>
