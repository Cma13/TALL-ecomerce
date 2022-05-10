<div class="container-menu py-12">
    <x-jet-form-section submit="save">
        <x-slot name="title">
            Crear nueva subcategoría
        </x-slot>
        <x-slot name="description">
            Complete la información necesaria para poder crear una nueva subcategoría
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label value="Nombre" />
                <x-jet-input wire:model="createForm.name" class="w-full mt-1" type="text" />
                <x-jet-input-error for="createForm.name" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label value="Slug" />
                <x-jet-input wire:model="createForm.slug" class="w-full mt-1 bg-gray-200" disabled type="text" />
                <x-jet-input-error for="createForm.slug" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <div class="flex items-center">
                    <p>¿Esta subcategoría necesita especificar color?</p>
                    <div class="ml-auto">
                        <label>
                            <input wire:model.defer="createForm.color" type="radio" value="1" name="color">
                            Sí
                        </label>
                        <label>
                            <input wire:model.defer="createForm.color" type="radio" value="0" name="color">
                            No
                        </label>
                    </div>
                </div>
                <x-jet-input-error for="createForm.color" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <div class="flex items-center">
                    <p>¿Esta subcategoría necesita especificar talla?</p>
                    <div class="ml-auto">
                        <label>
                            <input wire:model.defer="createForm.size" type="radio" value="1" name="size">
                            Sí
                        </label>
                        <label>
                            <input wire:model.defer="createForm.size" type="radio" value="0" name="size">
                            No
                        </label>
                    </div>
                </div>
                <x-jet-input-error for="createForm.size" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">Categoría creada</x-jet-action-message>
            <x-jet-button>Agregar</x-jet-button>
        </x-slot>
    </x-jet-form-section>
    <x-jet-action-section class="mt-6">
        <x-slot name="title">
            Lista de subcategorías
        </x-slot>
        <x-slot name="description">
            Aquí encontrará todas las subcategorías agregadas
        </x-slot>
        <x-slot name="content">
            <table class="text-gray-600">
                <thead class="border-b border-gray-300">
                    <tr class="text-left">
                        <th class="py-2 w-full">Nombre</th>
                        <th class="py-2">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($subcategories as $subcategory)
                        <tr>
                            <td class="py-2">
                                <span>{{ $subcategory->name }}</span>
                            </td>
                            <td class="py-2">
                                <div class="flex font-semibold divide-x divide-gray-300">
                                    <a class="pr-2 hover:text-blue-600 hover:underline cursor-pointer"
                                        wire:click="edit('{{ $subcategory->id }}')">Editar</a>
                                    <a class="pl-2 hover:text-red-600 hover:underline cursor-pointer"
                                        wire:click="$emit('deleteSubcategory', '{{ $subcategory->id }}')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-slot>
    </x-jet-action-section>
    <x-jet-dialog-modal wire:model="editForm.open">
        <x-slot name="title">Editar subcategoría</x-slot>
        <x-slot name="content">
            <div class="space-y-3">
                <div class="">
                    <x-jet-label value="Nombre" />
                    <x-jet-input wire:model="editForm.name" class="w-full mt-1" type="text" />
                    <x-jet-input-error for="editForm.name" />
                </div>
                <div class="">
                    <x-jet-label value="Slug" />
                    <x-jet-input wire:model="editForm.slug" class="w-full mt-1 bg-gray-200" disabled type="text" />
                    <x-jet-input-error for="editForm.slug" />
                </div>
                <div>
                    <div class="flex items-center">
                        <p>¿Esta subcategoría necesita especificar color?</p>
                        <div class="ml-auto">
                            <label>
                                <input wire:model.defer="editForm.color" type="radio" value="1" name="color">
                                Sí
                            </label>
                            <label>
                                <input wire:model.defer="editForm.color" type="radio" value="0" name="color">
                                No
                            </label>
                        </div>
                    </div>
                    <x-jet-input-error for="editForm.color" />
                </div>
                <div>
                    <div class="flex items-center">
                        <p>¿Esta subcategoría necesita especificar talla?</p>
                        <div class="ml-auto">
                            <label>
                                <input wire:model.defer="editForm.size" type="radio" value="1" name="size">
                                Sí
                            </label>
                            <label>
                                <input wire:model.defer="editForm.size" type="radio" value="0" name="size">
                                No
                            </label>
                        </div>
                    </div>
                    <x-jet-input-error for="createForm.size" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-action-message class="mr-3" on="updated">Subategoría editada</x-jet-action-message>
            <x-jet-danger-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
                Actualizar
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    @push('scripts')
        <script>
            Livewire.on('deleteSubcategory', subCategoryId => {
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
                        Livewire.emitTo('admin.show-category', 'delete', subCategoryId);
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
