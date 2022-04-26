<form action="{{ route('search') }}" autocomplete="off" method="get" class="relative flex-1">
    <x-jet-input name="name" wire:model="search" type="text" class="flex w-full" placeholder="¿Estás buscando algún producto?"></x-jet-input>

    <button class="absolute top-0 right-0 flex items-center justify-center w-12 h-full bg-orange-500 rounded-r-md">
        <x-search size="35" color="white"></x-search>
    </button>
    <div class="absolute hidden w-fullmt-1" :class="{ 'hidden' : !$wire.open }" @click.away="$wire.open = false">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-4 py-3 space-y-1">
                @forelse ($products as $product)
                    <a href="{{ route('products.show', $product) }}" class="flex">
                        <img class="object-cover w-16 h-12" src="{{ Storage::url($product->images->first()->url) }}">
                        <div class="ml-4 text-gray-700">
                            <p class="text-lg font-semibold leading-5">{{$product->name}}</p>
                            <p>Categoria: {{$product->subcategory->category->name}}</p>
                        </div>
                    </a>
                @empty
                <p class="text-lg leading-5">
                    No existe ningún registro con los parámetros especificados
                </p>
                @endforelse
            </div>
        </div>
    </div>
</form>
