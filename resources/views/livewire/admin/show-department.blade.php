<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ ucfirst($department->name) }}
        </h2>
    </x-slot>
    <div class="container-menu py-12">
        <x-jet-form-section submit="save" class="mb-6">
            <x-slot name="title">
                Agregar una nueva ciudad
            </x-slot>
            <x-slot name="description">
                En esta sección podrá agregar una nueva ciudad
            </x-slot>
            <x-slot name="form">
                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label value="Nombre" />
                    <x-jet-input type="text" wire:model.defer="createForm.name" class="w-full"
                        placeholder="Ingrese el nombre de la ciudad" />
                    <x-jet-input-error for="createForm.name" />
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label value="Costo" />
                    <x-jet-input type="number" wire:model.defer="createForm.cost" class="w-full"
                        placeholder="Ingrese el coste" step="0.1" />
                    <x-jet-input-error for="createForm.cost" />
                </div>
            </x-slot>
            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="saved">Ciudad agregada</x-jet-action-message>
                <x-jet-button>Agregar</x-jet-button>
            </x-slot>
        </x-jet-form-section>
        <x-jet-action-section class="mt-6">
            <x-slot name="title">
                Lista de ciudades
            </x-slot>
            <x-slot name="description">
                Aquí encontrará todas las ciudades agregadas
            </x-slot>
            <x-slot name="content">
                <table class="text-gray-600">
                    <thead class="border-b border-gray-300">
                        <tr class="text-left">
                            <th class="py-2 w-2/3">Nombre</th>
                            <th class="py-2 w-1/3">Coste</th>
                            <th class="py-2 w-1/3">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @foreach ($cities as $city)
                            <tr>
                                <td class="py-2">
                                    <span>{{ $city->name }}</span>
                                </td>
                                <td class="py-2 text-left w-2 ">
                                    <span>{{ $city->cost }}&euro;</span>
                                </td>
                                <td class="py-2">
                                    <div class="flex font-semibold divide-x divide-gray-300">
                                        <a class="pr-2 hover:text-blue-600 hover:underline cursor-pointer"
                                            wire:click="edit('{{ $city->id }}')">Editar</a>
                                        <a class="pl-2 hover:text-red-600 hover:underline cursor-pointer"
                                            wire:click="$emit('deleteCity', '{{ $city->id }}')">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-slot>
        </x-jet-action-section>
        <x-jet-dialog-modal wire:model="editForm.open">
            <x-slot name="title">Editar ciudad</x-slot>
            <x-slot name="content">
                <div class="space-y-3">
                    <div class="">
                        <x-jet-label value="Nombre" />
                        <x-jet-input wire:model="editForm.name" class="w-full mt-1" type="text" />
                        <x-jet-input-error for="editForm.name" />
                    </div>
                    <div class="">
                        <x-jet-label value="Costo" />
                        <x-jet-input wire:model="editForm.cost" class="w-full mt-1" type="number" step="0.1" />
                        <x-jet-input-error for="editForm.cost" />
                    </div>

                </div>
            </x-slot>
            <x-slot name="footer">
                <x-jet-action-message class="mr-3" on="updated">Ciudad editada</x-jet-action-message>
                <x-jet-danger-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
                    Actualizar
                </x-jet-danger-button>
            </x-slot>
        </x-jet-dialog-modal>

        @push('scripts')
            <script>
                Livewire.on('deleteCity', cityId => {
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
                            Livewire.emitTo('admin.show-department', 'delete', cityId);
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

</div>
