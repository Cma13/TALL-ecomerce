<div class="mt-4">
    <div class="bg-gray-100 rounded-lg shadow-lg p-6">
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
    @if ($size_colors->count())
        <div class="mt-8">
            <table class="table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2 w-1/3">Color</th>
                        <th class="px-4 py-2 w-1/3">Cantidad</th>
                        <th class="px-4 py-2 w-1/3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($size_colors as $size_color)
                        <tr wire:key="size_color-{{ $size_color->pivot->id }}">
                            <td class="px-4 py-2">
                                {{ __(ucfirst($colors->find($size_color->pivot->color_id)->name)) }}
                            </td>
                            <td class="px-4 py-2">{{ $size_color->pivot->quantity }} Uds</td>
                            <td class="px-4 py-2 flex">
                                <x-jet-secondary-button wire:click="edit({{ $size_color->pivot->id }})"
                                    class="ml-auto mr-2" wire:loading.attr="disabled"
                                    wire:target="edit({{ $size_color->pivot->id }})">
                                    Actualizar
                                </x-jet-secondary-button>
                                <x-jet-danger-button wire:click="$emit('deleteColorSize', {{ $size_color->pivot->id }})">
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
</div>
