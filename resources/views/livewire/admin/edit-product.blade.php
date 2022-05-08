<div>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="font-semibold text-lg text-gray-800 leading-tight">Productos</h1>
                <x-jet-danger-button wire:click="$emit('deleteProduct')">Eliminar</x-jet-danger-button>
            </div>
        </div>
    </header>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-gray-700">
        <h1 class="text-3xl text-center font-semibold mb-8">Complete esta información para crear un producto</h1>
        <div class="mb-4" wire:ignore>
            <form action="{{ route('admin.products.files', $product) }}" method="POST" class="dropzone"
                id="my-dropzone"></form>
        </div>

        @if ($product->images->count())
            <section class="bg-white shadow-lg rounded-lg p-6 mb-4">
                <h1 class="text-2xl text-center font-semibold mb-2">Imagenes del producto</h1>
                <ul class="flex flex-wrap">
                    @foreach ($product->images as $image)
                        <li class="relative" wire:key="image-{{ $image->id }}">
                            <img class="w-32 h-20 object-cover" src="{{ Storage::url($image->url) }}" alt="">
                            <x-jet-danger-button class="absolute right-2 top-2"
                                wire:click="deleteImage({{ $image->id }})" wire:loading.attr="disabled"
                                wire:target="deleteImage({{ $image->id }})">x
                            </x-jet-danger-button>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @livewire('admin.status-product', ['product' => $product], key('status-product-' . $product->id))

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
                <x-jet-button wire:loading.attr="disabled" wire:click="save" wire:target="save">
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
    @push('scripts')
        <script>
            Dropzone.options.myDropzone = {
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dictDefaultMessage: "Mueva una imagen al recuadro",
                acceptedFiles: 'image/*',
                paramName: "file", // The name that will be used to transfer the file
                maxFilesize: 2, // MB
                complete: function(file) {
                    this.removeFile(file);
                },
                queuecomplete: function() {
                    Livewire.emit('refreshProduct');
                }
            };
            Livewire.on('deleteSize', sizeId => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción no se puede revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, borralo!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.size-product', 'delete', sizeId);
                        Swal.fire(
                            'Borrado!',
                            'El registro ha sido borrado.',
                            'success'
                        )
                    }
                })
            })
            Livewire.on('deletePivot', pivot => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción no se puede revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, borralo!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.color-product', 'delete', pivot);
                        Swal.fire(
                            'Borrado!',
                            'El registro ha sido borrado.',
                            'success'
                        )
                    }
                })
            })
            Livewire.on('deleteColorSize', pivot => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción no se puede revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, borralo!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.color-size', 'delete', pivot);
                        Swal.fire(
                            'Borrado!',
                            'El registro ha sido borrado.',
                            'success'
                        )
                    }
                })
            })
            Livewire.on('deleteProduct', sizeId => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción no se puede revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, borralo!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.edit-product', 'delete');
                        Swal.fire(
                            'Borrado!',
                            'El registro ha sido borrado.',
                            'success'
                        )
                    }
                })
            })
        </script>
    @endpush
</div>
