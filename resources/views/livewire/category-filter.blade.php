<div>
    <div class="mb-6 bg-white rounded-lg shadow-lg">
        <div class="flex items-center justify-between px-6 py-2">
            <h1>{{ $category->name }}</h1>
            <div>
                <div class="grid grid-cols-2 text-gray-500 border border-gray-200 divide-x divide-gray-200">
                    <i class="fas fa-border-all p-3 cursor-pointer {{ $view == 'grid' ? 'text-orange-500' : '' }}" wire:click="$set('view', 'grid')"></i>
                    <i class="fas fa-th-list p-3 cursor-pointer {{ $view == 'list' ? 'text-orange-500' : '' }}" wire:click="$set('view', 'list')""></i>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
        <aside>
            <h2 class="mb-2 font-semibold text-center">Subcategorías</h2>
            <ul class="divide-y divide-gray-200">
                @foreach($category->subcategories as $subcategory)
                    <li class="py-2 text-sm ">
                        <a class="cursor-pointer hover:text-orange-500 capitalize {{ $subcategoria == $subcategory->slug ? 'text-orange-500 font-
                            semibold' : '' }}"
                        wire:click="$set('subcategoria', '{{ $subcategory->slug }}')">{{ $subcategory->name }}</a>
                    </li>
                @endforeach
            </ul>
            <h2 class="mt-4 mb-2 font-semibold text-center">Marcas</h2>
            <ul class="divide-y divide-gray-200">
                @foreach($category->brands as $brand)
                    <li class="py-2 text-sm ">
                        <a class="cursor-pointer hover:text-orange-500 capitalize {{ $marca == $brand->name ? 'text-orange-500 font-semibold' : ''
                        }}"
                        wire:click="$set('marca', '{{ $brand->name }}')">{{ $brand->name }}</a>
                    </li>
                @endforeach
            </ul>
            <x-jet-button class="mt-4" wire:click="limpiar">
                Eliminar Filtros
            </x-jet-button>
        </aside>
        <div class="md:col-span-2 lg:col-span-4">
            @if($view == 'grid')
                <ul class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    @forelse($products as $product)
                        <li class="bg-white rounded-lg shadow">
                            <article>
                                <figure>
                                    <img class="object-cover object-center w-full h-48"
                                    src="{{ Storage::url($product->images->first()->url) }}" alt="">
                                </figure>
                                <div class="px-6 py-4">
                                    <h1 class="text-lg font-semibold">
                                        <a href="{{ route('products.show', $product) }}">
                                            {{ Str::limit($product->name, 20) }}
                                        </a>
                                    </h1>
                                    <p class="font-bold text-trueGray-700">{{ $product->price }} &euro;</p>
                                </div>
                            </article>
                        </li>

                    @empty
                        <li class="md:col-span-2 lg:col-span-4">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Ups</strong>
                                <span class="block sm:inline">No existe ningún registro con ese filtro</span>
                              </div>
                        </li>
                    @endforelse
                </ul>
            @else
                <ul>
                    @forelse($products as $product)
                        <x-products-list :product="$product"></x-products-list>

                    @empty
                        <li class="md:col-span-2 lg:col-span-4">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Ups</strong>
                                <span class="block sm:inline">No existe ningún registro con ese filtro</span>
                            </div>
                        </li>
                    @endforelse
                </ul>
            @endif
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
