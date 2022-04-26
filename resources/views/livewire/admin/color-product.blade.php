<div>
    <div class="p-6 my-12 bg-white rounded-lg shadow-lg">
        {{-- Color --}}
        <div class="mb-6">
            <x-jet-label>
                Color
            </x-jet-label>

            <div class="grid grid-cols-6 gap-6">
                @foreach ($colors as $color)
                    <label>
                        <input type="radio" name="color_id" wire:model.defer='color_id' value="{{ $color->id }}">
                        <span class="ml-2 text-gray-700">{{ __(ucfirst($color->name)) }}</span>
                    </label>
                @endforeach
            </div>
            <x-jet-input-error for="color_id" />
        </div>
        {{-- Cantidad --}}
        <div>
            <x-jet-label>
                Cantidad
            </x-jet-label>
            <x-jet-input type="number" wire:model.defer='quantity' placeholder="Ingrese una cantidad"
                class="w-full" />
            <x-jet-input-error for="quantity" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-jet-action-message class="mr-3" on="saved">
                Agregado
            </x-jet-action-message>
            <x-jet-button wire:loading.attr="disabled" wire:target="save" wire:click="save">
                Agregar
            </x-jet-button>
        </div>
    </div>

    @if ($productColors->count())
        <div class="p-6 bg-white rounded-lg shadow-lg">
            <table>
                <thead>
                    <tr>
                        <th class="w-1/3 px-4 py-2">Color</th>
                        <th class="w-1/3 px-4 py-2">Cantidad</th>
                        <th class="w-1/3 px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productColors as $productColor)
                        <tr wire:key="productColor-{{ $productColor->pivot->id }}">
                            <td class="px-4 py-2">
                                {{ __(ucfirst($colors->find($productColor->pivot->color_id)->name)) }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $productColor->pivot->quantity }} unidades
                            </td>
                            <td class="flex px-4 py-2">
                                <x-jet-secondary-button class="ml-auto mr-2"
                                    wire:click="edit({{ $productColor->pivot->id }})" wire:loading.attr="disabled"
                                    wire:target="edit({{ $productColor->pivot->id }})">
                                    Actualizar
                                </x-jet-secondary-button>
                                <x-jet-danger-button
                                    wire:click="$emit('deleteColor', {{ $productColor->pivot->id }})">
                                    Eliminar
                                </x-jet-danger-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Editar colores
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <x-jet-label>
                    Color
                </x-jet-label>
                <select class="w-full form-control" wire:model="pivot_color_id">
                    <option value="">Seleccione un color</option>
                    @foreach ($colors as $color)
                        <option value="{{ $color->id }}">{{ __(ucfirst($color->name)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-jet-label>
                    Cantidad
                </x-jet-label>
                <x-jet-input type="number" wire:model="pivot_quantity" placeholder="Ingrese una cantidad"
                    class="w-full p-2" />
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire.target="update">
                Actualizar
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
