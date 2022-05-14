<div>
    @if ($sizes->count())
        @foreach ($sizes as $size)
            {{ $size->name }}
            <br>
        @endforeach
    @else
        Sin tallas aún
    @endif
</div>
