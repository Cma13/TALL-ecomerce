<div class="container-menu py-12">
    <x-jet-form-section submit="save" class="mb-6">
        <x-slot name="title">
            Agregar un nuevo departamento
        </x-slot>
        <x-slot name="description">
            En esta sección podrá agregar un nuevo departamento
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label value="Nombre" />
                <x-jet-input type="text" wire:model.defer="createForm.name" class="w-full"
                    placeholder="Ingrese el nombre del departamento" />
                <x-jet-input-error for="createForm.name" />
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">Departamento agregado</x-jet-action-message>
            <x-jet-button>Agregar</x-jet-button>
        </x-slot>
    </x-jet-form-section>
    <x-jet-action-section class="mt-6">
        <x-slot name="title">
            Lista de departamentos
        </x-slot>
        <x-slot name="description">
            Aquí encontrará todos los departamentos agregados
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
                    @foreach ($departments as $department)
                        <tr>
                            <td class="py-2">
                                <a class="hover:underline" href="{{ route('admin.departments.show', $department) }}">{{ $department->name }}</a>
                            </td>
                            <td class="py-2">
                                <div class="flex font-semibold divide-x divide-gray-300">
                                    <a class="pr-2 hover:text-blue-600 hover:underline cursor-pointer"
                                        wire:click="edit('{{ $department->id }}')">Editar</a>
                                    <a class="pl-2 hover:text-red-600 hover:underline cursor-pointer"
                                        wire:click="$emit('deleteDepartment', '{{ $department->id }}')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-slot>
    </x-jet-action-section>
    <x-jet-dialog-modal wire:model="editForm.open">
        <x-slot name="title">Editar departamento</x-slot>
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
            <x-jet-action-message class="mr-3" on="updated">Departamento editado</x-jet-action-message>
            <x-jet-danger-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
                Actualizar
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    @push('scripts')
        <script>
            Livewire.on('deleteDepartment', departmentId => {
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
                        Livewire.emitTo('admin.department-component', 'delete', departmentId);
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
