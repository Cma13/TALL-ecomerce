<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>
    <div class="container-menu py-12">
        <x-table-responsive>
            <div class="px-6 py-4">
                <x-jet-input wire:model="search" type="text" class="w-full"
                    placeholder="Escriba algo para filtrar" />
            </div>

            @if ($users->count())
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                ID
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Nombre
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Email
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Rol
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <a class="sr-only cursor-pointer">Editar</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr wire:key="{{ $user->email }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-700">
                                        {{ $user->id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-700">
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-700">
                                        {{ $user->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    <div class="text-gray-700">
                                        @if ($user->roles->count())
                                            Admin
                                        @else
                                            No tiene rol
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                    <label>
                                        <input {{ count($user->roles) ? 'checked' : '' }} value="1" type="radio"
                                            name="{{ $user->email }}"
                                            wire:change="assignRole({{ $user->id }}, $event.target.value)">
                                        Si
                                    </label>
                                    <label class="ml-2">
                                        <input {{ count($user->roles) ? '' : 'checked' }} value="0" type="radio"
                                            name="{{ $user->email }}"
                                            wire:change="assignRole({{ $user->id }}, $event.target.value)">
                                        No
                                    </label>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-4">
                    No hay ningún registro coincidente
                </div>
            @endif
            @if ($users->hasPages())
                <div class="px-6 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </x-table-responsive>
    </div>
</div>
