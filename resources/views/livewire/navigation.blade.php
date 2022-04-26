<header class="sticky top-0 z-50 bg-neutral-700" style="z-index: 900" x-data="dropdown()">
    <div class="flex items-center justify-between h-16 container-menu md:justify-start">
        <a :class="{'bg-opacity-100 text-orange-500':open}" dusk="categoriasLink" x-on:click="show()"
            class="flex flex-col items-center justify-center order-last h-full px-6 font-semibold text-white bg-white bg-opacity-25 cursor-pointer md:order-first sm:px-4">
            <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="hidden text-sm sm:block">
                Categorías
            </span>
        </a>
        <a href="/" class="mx-6">
            <x-jet-application-mark class="block w-auto h-9"></x-jet-application-mark>
        </a>

        <div class="flex-1 hidden md:block">
            @livewire('search')
        </div>

        <div class="relative hidden mx-6 md:block">
            @auth
                <x-jet-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex text-sm transition border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300">
                            <img class="object-cover w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
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
                        <x-jet-dropdown-link href="{{ route('orders.index') }}">
                            {{ __('My Orders') }}
                        </x-jet-dropdown-link>
                        <x-jet-dropdown-link href="{{ route('admin.index') }}">
                            {{ __('Admin') }}
                        </x-jet-dropdown-link>
                        <div class="border-t border-gray-100"></div>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-jet-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-jet-dropdown-link>
                        </form>
                    </x-slot>
                </x-jet-dropdown>
            @else
                <x-jet-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <i dusk="loginLink" class="text-3xl text-white cursor-pointer fas fa-user-circle"></i>
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

        <div class="hidden md:block">
            @livewire('dropdown-cart')
        </div>

    </div>
    <nav id="navigation-menu" x-show="open" :class="{'block': open, 'hidden': !open}"
        class="absolute hidden w-full bg-opacity-25 bg-neutral-700">
        <div class="hidden h-full container-menu sm:block">
            <div x-on:click.away="close()" class="relative grid h-full grid-cols-4">
                <ul class="bg-white">
                    @foreach ($categories as $category)
                        <li class="navigation-link text-neutral-500 hover:bg-orange-500 hover:text-white">
                            <a href="{{ route('categories.show', $category) }}"
                                class="flex items-center px-4 py-2 text-sm" dusk="category_{{ $category->name }}">
                                <span class="flex justify-center w-9">
                                    {!! $category->icon !!}
                                </span>
                                {{ $category->name }}
                            </a>
                            <div class="absolute top-0 right-0 hidden w-3/4 h-full bg-gray-500 navigation-submenu">
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
        <div class="h-full overflow-y-auto bg-white">
            <div class="py-3 mb-2 bg-gray-200 container-menu">
                @livewire('search')
            </div>
            <ul class="bg-white">
                @foreach ($categories as $category)
                    <li class="text-trueGray-500 hover:bg-orange-500 hover:text-white">
                        <a href="{{ route('categories.show', $category) }}"
                            class="flex items-center px-4 py-2 text-sm">
                            <span class="flex justify-center w-9">
                                {!! $category->icon !!}
                            </span>
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <p class="px-6 my-2 text-trueGray-500">USUARIOS</p>

            @livewire('cart-movil')

            @auth
                <a href="{{ route('profile.show') }}"
                    class="flex items-center px-4 py-2 text-sm text-trueGray-500 hover:bg-orange-500 hover:text-white">
                    <span class="flex justify-center w-9">
                        <i class="far fa-address-card"></i>
                    </span>
                    Perfil
                </a>
                <a href="" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit()"
                    class="flex items-center px-4 py-2 text-sm text-trueGray-500 hover:bg-orange-500 hover:text-white">
                    <span class="flex justify-center w-9">
                        <i class="fas fa-sign-out-alt"></i>
                    </span>
                    Cerrar sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="flex items-center px-4 py-2 text-sm text-trueGray-500 hover:bg-orange-500 hover:text-white">
                    <span class="flex justify-center w-9">
                        <i class="fas fa-user-circle"></i>
                    </span>
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}"
                    class="flex items-center px-4 py-2 text-sm text-trueGray-500 hover:bg-orange-500 hover:text-white">
                    <span class="flex justify-center w-9">
                        <i class="fas fa-fingerprint"></i>
                    </span>
                    Registrar
                </a>
            @endauth
        </div>
    </nav>
</header>
