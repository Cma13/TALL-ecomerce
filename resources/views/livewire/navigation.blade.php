<header class="bg-neutral-700 sticky top-0 z-50" x-data="dropdown()">
    <div class="container flex items-center h-16 justify-between md:justify-start">
        <a :class="{'bg-opacity-100 text-orange-500' : open}"
            class="flex flex-col items-center bg-white bg-opacity-25 text-white cursor-pointer semibold h-full justify-center px-6 md:px-4 order-last md:order-first"
            x-on:click="show()">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'inline-flex': ! open }" class="inline-flex" stroke-linecap="round"
                    stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-sm hidden md:block">Categorías</span>
        </a>
        <a href="/" class="mx-6">
            <x-jet-application-mark class="block h-9 w-auto" />
        </a>

        <div class="flex-1 hidden md:block">@livewire('search')</div>

        <div class="mx-6 relative hidden md:block">
            @auth
                <x-jet-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                                alt="{{ Auth::user()->name }}" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Account Management -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Account') }}
                        </div>

                        <x-jet-dropdown-link href="{{ route('profile.show') }}">
                            {{ __('Profile') }}
                        </x-jet-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <x-jet-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-jet-dropdown-link>
                        </form>
                    </x-slot>
                </x-jet-dropdown>
            @else
                <x-jet-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <i class="fas fa-user-circle text-white text-3xl cursor-pointer"></i>
                    </x-slot>

                    <x-slot name="content">
                        <x-jet-dropdown-link href="{{ route('login') }}">
                            {{ __('Login') }}
                        </x-jet-dropdown-link>
                        <x-jet-dropdown-link href="{{ route('register') }}">
                            {{ __('Register') }}
                        </x-jet-dropdown-link>
                    </x-slot>
                </x-jet-dropdown>

            @endauth
        </div>

        <div class="hidden md:block">@livewire('dropdown-cart')</div>
    </div>
    <nav id="navigation-menu" x-show="open" :class="{'block': open, 'hidden': !open}"
        class="bg-neutral-700 bg-opacity-25 absolute w-full hidden">
        {{-- Menú pc --}}
        <div class="container h-full hidden md:block">
            <div class="grid grid-cols-4 h-full relative" x-on:click.away="close()">
                <ul class="bg-white">
                    @foreach ($categories as $category)
                        <li class="navigation-link text-neutral-500 hover:bg-orange-500 hover:text-white">
                            <a href="" class="py-2 px-4 text-sm flex items-center">
                                <span class="flex justify-center w-9">{!! $category->icon !!}</span>
                                {{ $category->name }}
                            </a>
                            <div class="navigation-submenu bg-gray-100 absolute w-3/4 top-0 right-0 h-full hidden">
                                <x-navigation-subcategories :category="$category" />
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="col-span-3 bg-gray-100">
                    <x-navigation-subcategories :category="$categories->first()" />
                </div>
            </div>
        </div>
        {{-- Menún móvil --}}
        <div class="bg-white h-full overflow-y-auto">
            <div class="container bg-gray-200 py-3 mb-2">
                @livewire('search')
            </div>
            <ul>
                @foreach ($categories as $category)
                    <li class="text-neutral-500 hover:bg-orange-500 hover:text-white">
                        <a href="" class="py-2 px-4 text-sm flex items-center">
                            <span class="flex justify-center w-9">{!! $category->icon !!}</span>
                            {{ $category->name }}
                        </a>
                        <div class="navigation-submenu bg-gray-100 absolute w-3/4 top-0 right-0 h-full hidden">
                            <x-navigation-subcategories :category="$category" />
                        </div>
                    </li>
                @endforeach
            </ul>
            <p class="text-neutral-500 px-6 my-2">USUARIOS</p>

            @livewire('cart-mobile')

            @auth()
                <a href="{{ route('profile.show') }}"
                    class="py-2 px-4 text-sm flex items-center text-neutral-500 hover:bg-orange-500 hover:text-white">
                    <span class="flex justify-center w-9"><i class="fa-solid fa-address-card"></i></span>
                    Perfil
                </a>
                <a href="{{ route('logout') }}"
                    class="py-2 px-4 text-sm flex items-center text-neutral-500 hover:bg-orange-500 hover:text-white"
                    onclick="event.preventDefault();
                                document.getElementById('logout-form').submit()">
                    <span class="flex justify-center w-9"><i class="fa-solid fa-right-from-bracket"></i></span>
                    Cerrar sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="py-2 px-4 text-sm flex items-center text-neutral-500 hover:bg-orange-500 hover:text-white">
                    <span class="flex justify-center w-9"><i class="fa-solid fa-circle-user"></i></span>
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}"
                    class="py-2 px-4 text-sm flex items-center text-neutral-500 hover:bg-orange-500 hover:text-white">
                    <span class="flex justify-center w-9"><i class="fa-solid fa-fingerprint"></i></span>
                    Regístrate
                </a>
            @endauth
        </div>
    </nav>
</header>
