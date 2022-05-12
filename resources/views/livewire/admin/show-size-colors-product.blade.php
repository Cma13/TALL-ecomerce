<div class="flex">
    @foreach ($colors as $color)
        <div class="mr-1">
            <x-color-circle color="{{ $color }}" />
        </div>
    @endforeach
</div>
