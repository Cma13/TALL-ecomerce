<div>
    <div class="bg-white rounded-lg shadow-lg my-12 p-6">
        <div clasS="grid grid-cols-2 gap-6">
            {{-- Color --}}
            <div>
                <x-jet-label value="Color" />
                <select wire:model.defer="color_id" class="w-full form-control">
                    <option value="" selected disabled>Seleccione un color</option>
                    @foreach ($colors as $color)
                        <option value="{{ $color->id }}">{{ __(ucfirst($color->name)) }}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="color_id" />
            </div>
            {{-- Cantidad --}}
            <div>
                <x-jet-label value="Cantidad" />
                <x-jet-input type="number" class="w-full" wire:model.defer="quantity"
                    placeholder="Ingrese una cantidad" />
                <x-jet-input-error for="quantity" />
            </div>
        </div>
        <div class="flex mt-4 justify-end items-center">
            <x-jet-action-message class="mr-3" on="saved">Agregado</x-jet-action-message>
            <x-jet-button wire:loading.attr="disabled" wire:target="save" wire:click="save">
                Agregar
            </x-jet-button>
        </div>
    </div>
    @if($product_colors->count())
        <div class="bg-white rounded-lg shadow-lg p-6">
            <table class="table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2 w-1/3">Color</th>
                        <th class="px-4 py-2 w-1/3">Cantidad</th>
                        <th class="px-4 py-2 w-1/3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product_colors as $product_color)
                        <tr wire:key="product_color-{{ $product_color->pivot->id }}">
                            <td class="px-4 py-2">
                                {{ __(ucfirst($colors->find($product_color->pivot->color_id)->name)) }}
                            </td>
                            <td class="px-4 py-2">{{ $product_color->pivot->quantity }} Uds</td>
                            <td class="px-4 py-2 flex">
                                <x-jet-secondary-button wire:click="edit({{ $product_color->pivot->id }})"
                                    class="ml-auto mr-2" wire:loading.attr="disabled"
                                    wire:target="edit({{ $product_color->pivot->id }})">
                                    Actualizar
                                </x-jet-secondary-button>
                                <x-jet-danger-button wire:click="$emit('deletePivot', {{ $product_color->pivot->id }})">
                                    Eliminar</x-jet-danger-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <x-jet-dialog-modal wire:model="open" class="my-12">
        <x-slot name="title">
            Editar colores
        </x-slot>
        <x-slot name="content">
            <div>
                <x-jet-label value="Color" />
                <select wire:model.defer="pivot_color_id" class="w-full form-control">
                    <option value="" selected disabled>Seleccione un color</option>
                    @foreach ($colors as $color)
                        <option value="{{ $color->id }}">{{ __(ucfirst($color->name)) }}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="color_id" />
            </div>
            <div>
                <x-jet-label value="Cantidad" />
                <x-jet-input type="number" class="w-full" wire:model.defer="pivot_quantity"
                    placeholder="Ingrese una cantidad" />
                <x-jet-input-error for="quantity" />
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="flex justify-end items-center">
                <x-jet-button class="mr-4" wire:click="update" wire:loading.attr="disabled"
                    wire:target="update">Actualizar color</x-jet-button>
                <x-jet-secondary-button wire:click="$set('open', false)">Cancelar
                </x-jet-secondary-button>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    @push('scripts')
        <script>
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
                        Livewire.emit('delete', pivot);
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
