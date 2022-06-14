<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-600 leading-tight">Lista de productos</h2>
            <x-button-red href="{{ route('admin.products.create') }}">Agregar producto</x-button-red>
        </div>
    </x-slot>
    <div class="container-menu py-12">
        <div class="bg-white rounded-lg shadow-lg py-6 flex mb-4">
            <div x-data="{ openPages: false }" class="px-6">
                <button @click="openPages = ! openPages" class="rounded bg-blue-600 font-bold px-4 py-2 text-white">Filas
                    por página</button>

                <div x-show="openPages" @click.outside="openPages = false" class="bg-gray-100 rounded p-5 w-72">
                    <x-jet-label value="Introduzca cuantas filas quiere: " />
                    <div class="flex items-center">
                        <x-jet-input wire:model="pages" type="range" min="1" max="18" />
                        <span class="px-3 ml-3 bg-sky-600 text-white font-semibold rounded">{{ $pages }}</span>
                    </div>
                </div>
            </div>
            <div x-data="{ openColumns: false }" class="px-6">
                <button @click="openColumns = ! openColumns"
                    class="rounded bg-blue-600 font-bold px-4 py-2 text-white">Columnas a enseñar</button>

                <div x-show="openColumns" @click.outside="openColumns = false" class="bg-gray-100 rounded p-5 w-72">
                    @foreach ($columns as $column)
                        <x-jet-input type="checkbox" wire:model="selectedColumns" value="{{ $column }}" />
                        <label>{{ $column }}</label>
                        <br>
                    @endforeach
                </div>
            </div>
            <div x-data="{ openFilters: false }" class="px-6">
                <button @click="openFilters = ! openFilters"
                    class="rounded bg-blue-600 font-bold px-4 py-2 text-white">Filtros avanzados</button>

                <div x-show="openFilters" @click.outside="openFilters = false" class="bg-gray-100 rounded p-5 w-full">
                    <div class="grid grid-cols-4 grid-rows-2">
                        <div class="w-full">
                            <x-jet-label value="Categorías" />
                            @foreach ($categories as $category)
                                <x-jet-input type="checkbox" wire:model="selectedCategories"
                                    value="{{ $category->id }}" />
                                <label class="w-full">{{ $category->name }}</label>
                                <br>
                            @endforeach
                        </div>
                        <div class="w-full">
                            <x-jet-label value="Subcategorías" />
                            @foreach ($subcategories as $subcategory)
                                <x-jet-input type="checkbox" wire:model="selectedSubcategories"
                                    value="{{ $subcategory->id }}" />
                                <label class="w-full">{{ $subcategory->name }}</label>
                                <br>
                            @endforeach
                        </div>
                        <div>
                            <x-jet-label value="Marcas" />
                            @foreach ($brands as $brand)
                                <x-jet-input type="checkbox" wire:model="selectedBrands" value="{{ $brand->id }}" />
                                <label class="w-full">{{ $brand->name }}</label>
                                <br>
                            @endforeach
                        </div>
                        <div>
                            <x-jet-label value="Colores" />
                            @foreach ($colors as $color)
                                <x-jet-input type="checkbox" wire:model="selectedColors" value="{{ $color->id }}" />
                                <label class="w-full">{{ __(ucfirst($color->name)) }}</label>
                                <br>
                            @endforeach
                        </div>
                        <div>
                            <x-jet-label value="Tallas" />
                            @foreach ($sizes as $size)
                                <x-jet-input type="checkbox" wire:model="selectedSizes" value="{{ $size }}" />
                                <label class="w-full">{{ $size }}</label>
                                <br>
                            @endforeach
                        </div>
                        <div>
                            <div>
                                <x-jet-label value="Precio mínimo" />
                                <div class="flex items-center">
                                    <x-jet-input wire:model="minPriceFilter" type="range" min="0" max="50" />
                                    <span
                                        class="px-3 ml-3 bg-sky-600 text-white font-semibold rounded">{{ $minPriceFilter }}</span>
                                </div>
                            </div>
                            <div>
                                <x-jet-label value="Precio máximo" />
                                <div class="flex items-center">
                                    <x-jet-input wire:model="maxPriceFilter" type="range" min="51" max="100" />
                                    <span
                                        class="px-3 ml-3 bg-sky-600 text-white font-semibold rounded">{{ $maxPriceFilter }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <x-jet-label value="Desde" />
                                <div class="flex items-center">
                                    <x-jet-input wire:model="fromFilter" type="date" />
                                </div>
                            </div>
                            <div>
                                <x-jet-label value="Hasta" />
                                <div class="flex items-center">
                                    <x-jet-input wire:model="toFilter" type="date" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-table-responsive>
            <div class="px-6 py-4">
                <x-jet-input type="text" wire:model="search" class="w-full"
                    placeholder="Ingrese el nombre del producto que quiere buscar" />
            </div>
            @if ($products->count())
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        @foreach ($columns as $column)
                            @if ($this->showColumn($column))
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    <div class="flex items-center">
                                        <a wire:click="sortColumns('{{ $column }}')"
                                            class="hover:underline cursor-pointer">
                                            {{ $column }}
                                            <span class="ml-2">
                                                <a>
                                                    <i
                                                        class="fas fa-arrow-up {{ $sortDirection == 'asc' && $this->isColored($column) ? 'text-orange-600' : 'text-gray-300' }}"></i></a>
                                                <a>
                                                    <i
                                                        class="fas fa-arrow-down {{ $sortDirection == 'desc' && $this->isColored($column) ? 'text-orange-600' : 'text-gray-300' }}"></i></a>
                                            </span>
                                        </a>
                                    </div>
                                </th>
                            @endif
                        @endforeach
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Editar</span>
                        </th>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($products as $product)
                            <tr>
                                @if ($this->showColumn('Nombre'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 object-cover w-10 h-10">
                                                <img class="w-10 h-10 rounded-full"
                                                    src="{{ $product->images->count() ? Storage::url($product->images->first()->url) : 'img/default.jpg' }}"
                                                    alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $product->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Categoría'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $product->subcategory->category->name }}
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Subcategoría'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $product->subcategory->name }}
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Marca'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $product->brand->name }}
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Fecha de creación'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($product->created_at)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Estado'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($product->status)
                                            @case(1)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Borrador
                                                </span>
                                            @break

                                            @case(2)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Publicado
                                                </span>
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                @endif
                                @if ($this->showColumn('Colores'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (!$product->subcategory->color)
                                            No tiene color
                                        @elseif ($product->subcategory->size)
                                            @livewire('admin.show-size-colors-product', ['product' => $product], key('size-color-product-' . $product->id))
                                        @else
                                            @livewire('admin.show-colors-product', ['product' => $product], key('color-product-' . $product->id))
                                        @endif
                                    </td>
                                @endif
                                @if ($this->showColumn('Tallas'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (!$product->subcategory->size)
                                            No tiene talla
                                        @else
                                            @livewire('admin.show-size-product', ['product' => $product], key('size-product-' . $product->id))
                                        @endif
                                    </td>
                                @endif
                                @if ($this->showColumn('Stock'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (!$product->subcategory->color)
                                            {{ $product->quantity }}
                                        @else
                                            @livewire('admin.show-qty-product', ['product' => $product], key('product-' . $product->id))
                                        @endif
                                    </td>
                                @endif
                                @if ($this->showColumn('Precio'))
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $product->price }}&euro;
                                    </td>
                                @endif
                                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-4">
                    No hay ningún registro coincidente
                </div>
            @endif

            @if ($products->hasPages())
                <div class="px-6 py-4">
                    {{ $products->links() }}
                </div>
            @endif
        </x-table-responsive>
    </div>
</div>
