<div class="container-menu py-12">
    <x-jet-form-section submit="save" class="mb-6">
        <x-slot name="title">
            Agregar nueva marca
        </x-slot>
        <x-slot name="description">
            En esta sección podrá agregar una nueva marca
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label value="Nombre" />
                <x-jet-input type="text" wire:model="createForm.name" class="w-full"
                    placeholder="Ingrese el nombre de la marca" />
                <x-jet-input-error for="createForm.name" />
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-jet-button>Agregar</x-jet-button>
        </x-slot>
    </x-jet-form-section>
    <x-jet-action-section class="mt-6">
        <x-slot name="title">
            Lista de marcas
        </x-slot>
        <x-slot name="description">
            Aquí encontrará todas las marcas agregadas
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
                    @foreach ($brands as $brand)
                        <tr>
                            <td class="py-2">
                                <span>{{ $brand->name }}</span>
                            </td>
                            <td class="py-2">
                                <div class="flex font-semibold divide-x divide-gray-300">
                                    <a class="pr-2 hover:text-blue-600 hover:underline cursor-pointer"
                                        wire:click="edit('{{ $brand->id }}')">Editar</a>
                                    <a class="pl-2 hover:text-red-600 hover:underline cursor-pointer"
                                        wire:click="$emit('deleteBrand', '{{ $brand->id }}')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-slot>
    </x-jet-action-section>
    <x-jet-dialog-modal wire:model="editForm.open">
        <x-slot name="title">Editar marca</x-slot>
        <x-slot name="content">
            <div class="space-y-3">
                <div class="">
                    <x-jet-label value="Nombre" />
                    <x-jet-input wire:model="editForm.name" class="w-full mt-1" type="text" />
                    <x-jet-input-error for="editForm.name" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-action-message class="mr-3" on="updated">Marca editada</x-jet-action-message>
            <x-jet-danger-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
                Actualizar
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    @push('scripts')
        <script>
            Livewire.on('deleteBrand', brandId => {
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
                        Livewire.emitTo('admin.brand-component', 'delete', brandId);
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
