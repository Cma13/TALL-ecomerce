<div>
    <div class="bg-white shadow-lg rounded-lg p-6 mt-12">
        <div>
            <x-jet-label value="Talla" />
            <x-jet-input type="text" placeholder="Ingrese una talla" class="w-full" wire:model="name" />
            <x-jet-input-error for="name" />
        </div>
        <div class="flex justify-end items-center mt-4">
            <x-jet-button wire:click="save" wire:loading.attr="disabled" wire:target="save">Agregar</x-jet-button>
        </div>
    </div>
    <ul class="mt-12 space-y-4">
        @foreach ($sizes as $size)
            <li class="bg-white shadow-lg rounded-lg p-6" wire:key="size-{{ $size->id }}">
                <div class="flex items-center">
                    <span class="text-xl font-medium">{{ $size->name }}</span>
                    <div class="ml-auto">
                        <x-jet-button wire:click="edit({{ $size->id }})" wire:loading.attr="disabled"
                            wire:target="edit({{ $size->id }})"><i class="fas fa-edit"></i>
                        </x-jet-button>
                        <x-jet-danger-button wire:click="$emit('deleteSize', {{ $size->id }})"><i
                                class="fas fa-trash"></i></x-jet-danger-button>
                    </div>
                </div>
                @livewire('admin.color-size', ['size' => $size], key('color-size' . $size->id))
            </li>
        @endforeach
    </ul>


    <x-jet-dialog-modal wire:model="open" class="my-12">
        <x-slot name="title">
            Editar talla
        </x-slot>
        <x-slot name="content">
            <div>
                <x-jet-label value="Talla" />
                <x-jet-input type="text" class="w-full" wire:model="name_edit" />
                <x-jet-input-error for="name_edit" />
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="flex justify-end items-center">
                <x-jet-button class="mr-4" wire:click="update" wire:loading.attr="disabled"
                    wire:target="update">Actualizar talla</x-jet-button>
                <x-jet-secondary-button wire:click="$set('open', false)">Cancelar
                </x-jet-secondary-button>
            </div>
        </x-slot>
    </x-jet-dialog-modal>
</div>
