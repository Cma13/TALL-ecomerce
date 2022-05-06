<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-gray-700">
    <h1 class="text-3xl text-center font-semibold mb-8">Complete esta información para crear un producto</h1>
    <div class="rounded-lg shadow-lg bg-white p-6">
        <div class="grid grid-cols-2 gap-6 mb-4">
            {{-- Categoría --}}
            <div>
                <x-jet-label value="Categorías" />
                <select class="w-full form-control" wire:model="category_id">
                    <option value="" disabled selected>Seleccione una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="category_id" />
            </div>
            {{-- Subcategoría --}}
            <div>
                <x-jet-label value="Subcategorías" />
                <select class="w-full form-control" wire:model="product.subcategory_id">
                    <option value="" disabled selected>Seleccione una subcategoría</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="product.subcategory_id" />
            </div>
        </div>
        {{-- Nombre --}}
        <div class="mb-4">
            <x-jet-label value="Nombre" />
            <x-jet-input type="text" class="w-full" placeholder="Ingrese el nombre del producto"
                wire:model="product.name" />
            <x-jet-input-error for="product.name" />
        </div>
        {{-- Slug --}}
        <div class=" mb-4">
            <x-jet-label value="Slug" />
            <x-jet-input type="text" disabled class="w-full bg-gray-200" placeholder="Ingrese el slug del producto"
                wire:model="product.slug" />
            <x-jet-input-error for="product.slug" />
        </div>
        {{-- Descripción --}}
        <div class="mb-4">
            <div wire:ignore>
                <x-jet-label value="Descripción" />
                <textarea wire:model="product.description" class="w-full form-control" rows="4" x-data x-init="ClassicEditor
                    .create($refs.miEditor)
                    .then(function(editor) {
                        editor.model.document.on('change:data', () => {
                            @this.set('product.description', editor.getData())
                        })
                    })
                    .catch(error => {
                        console.error(error);
                    });"
                    x-ref="miEditor"></textarea>
            </div>
            <x-jet-input-error for="product.description" />
        </div>
        <div class="grid grid-cols-2 gap-6 mb-4">
            {{-- Marca --}}
            <div>
                <x-jet-label value="Marca" />
                <select class="w-full form-control" wire:model="product.brand_id">
                    <option value="" disabled selected>Seleccione una marca</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="product.brand_id" />
            </div>
            {{-- Precio --}}
            <div>
                <x-jet-label value="Precio" />
                <x-jet-input type="number" class="w-full" wire:model="product.price" step=".01" />
                <x-jet-input-error for="product.price" />
            </div>
        </div>

        @if ($this->subcategory)
            @if (!$this->subcategory->color && !$this->subcategory->size)
                {{-- Cantidad --}}
                <div>
                    <x-jet-label value="Cantidad" />
                    <x-jet-input type="number" class="w-full" wire:model="product.quantity"
                        placeholder="Ingrese una cantidad" />
                    <x-jet-input-error for="product.quantity" />
                </div>
            @endif
        @endif

        <div class="flex mt-4 justify-end items-center">
            <x-jet-action-message class="mr-3" on="saved">Actualizado</x-jet-action-message>
            <x-jet-button wire:loading.attr="disabled" wire:target="save" wire:click="save">
                Editar producto
            </x-jet-button>
        </div>
    </div>
    
    @if ($this->subcategory)
        @if ($this->subcategory->size)
            @livewire('admin.size-product', ['product' => $product], key('size-product' . $product->id))
        @elseif ($this->subcategory->color)
            @livewire('admin.color-product', ['product' => $product], key('color-product' . $product->id))
        @endif
    @else
    @endif
</div>
