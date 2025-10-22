<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    {{-- Sidebar --}}
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 z-10 flex flex-col">
        {{-- Contenido superior (logo y navegación) --}}
        <div>
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse px-4 py-2" wire:navigate>
                <img 
                    src="{{ asset('images/nexo.png') }}" 
                    alt="Nexo Emprendedores" 
                    class="w-32 h-16 dark:drop-shadow-[0_0_15px_rgba(255,255,255,0.95)] transition duration-300"
                />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Plataforma')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Panel') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
        </div>

        <flux:spacer />

        {{-- Panel de usuario ESCRITORIO --}}
        <div class="hidden lg:flex flex-col w-full p-2 gap-2">
            {{-- Fila superior: campanita izquierda, modo oscuro derecha --}}
            <div class="flex items-center justify-between w-full">
                <livewire:notifications-bell />

                <button 
                    @click="$store.theme.toggle()"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition"
                    aria-label="Alternar modo oscuro"
                >
                    <svg x-show="!$store.theme.dark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a.75.75 0 01.75.75V4a.75.75 0 01-1.5 0V2.75A.75.75 0 0110 2zM10 16a.75.75 0 01.75.75V18a.75.75 0 01-1.5 0v-1.25A.75.75 0 0110 16zM4 9.25a.75.75 0 000 1.5H2.75a.75.75 0 000-1.5H4zM17.25 9.25a.75.75 0 000 1.5H19a.75.75 0 000-1.5h-1.75zM4.22 4.22a.75.75 0 011.06 0L6.5 5.44a.75.75 0 11-1.06 1.06L4.22 5.28a.75.75 0 010-1.06zM14.56 14.56a.75.75 0 011.06 0l1.22 1.22a.75.75 0 01-1.06 1.06l-1.22-1.22a.75.75 0 010-1.06zM14.56 5.44a.75.75 0 010-1.06l1.22-1.22a.75.75 0 111.06 1.06L15.62 5.44a.75.75 0 01-1.06 0zM4.22 15.78a.75.75 0 010-1.06l1.22-1.22a.75.75 0 111.06 1.06L5.28 15.78a.75.75 0 01-1.06 0z"></path>
                    </svg>
                    <svg x-show="$store.theme.dark" style="display: none;" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707 8 8 0 1017.293 13.293z"></path>
                    </svg>
                </button>
            </div>

            {{-- Fila inferior: perfil con menú --}}
            <flux:dropdown class="flex-grow" position="top" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                />
                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Ajustes') }}</flux:menu.item>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>

        {{-- Panel de usuario MÓVIL --}}
        <div class="flex lg:hidden flex-col w-full p-2 gap-2">
            {{-- Fila superior: campanita izquierda, modo oscuro derecha --}}
            <div class="flex items-center justify-between w-full">
                <livewire:notifications-bell />

                <button 
                    @click="$store.theme.toggle()"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition"
                    aria-label="Alternar modo oscuro"
                >
                    <svg x-show="!$store.theme.dark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a.75.75 0 01.75.75V4a.75.75 0 01-1.5 0V2.75A.75.75 0 0110 2zM10 16a.75.75 0 01.75.75V18a.75.75 0 01-1.5 0v-1.25A.75.75 0 0110 16zM4 9.25a.75.75 0 000 1.5H2.75a.75.75 0 000-1.5H4zM17.25 9.25a.75.75 0 000 1.5H19a.75.75 0 000-1.5h-1.75zM4.22 4.22a.75.75 0 011.06 0L6.5 5.44a.75.75 0 11-1.06 1.06L4.22 5.28a.75.75 0 010-1.06zM14.56 14.56a.75.75 0 011.06 0l1.22 1.22a.75.75 0 01-1.06 1.06l-1.22-1.22a.75.75 0 010-1.06zM14.56 5.44a.75.75 0 010-1.06l1.22-1.22a.75.75 0 111.06 1.06L15.62 5.44a.75.75 0 01-1.06 0zM4.22 15.78a.75.75 0 010-1.06l1.22-1.22a.75.75 0 111.06 1.06L5.28 15.78a.75.75 0 01-1.06 0z"></path>
                    </svg>
                    <svg x-show="$store.theme.dark" style="display: none;" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707 8 8 0 1017.293 13.293z"></path>
                    </svg>
                </button>
            </div>

            {{-- Fila inferior: perfil con menú --}}
            <flux:dropdown class="flex-grow" position="top" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                />
                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Ajustes') }}</flux:menu.item>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:sidebar>

    {{-- Header móvil --}}
    <flux:header class="lg:hidden z-10">
        {{-- Contenido del header móvil --}}
        <flux:sidebar.toggle icon="bars-3" />
        {{-- Agregar contenido aquí --}}
    </flux:header>

    {{-- Contenido principal --}}
    {{ $slot }}
    
    {{-- Widget de accesibilidad --}}
    <livewire:accesibilidad-widget desktopPositionClass="lg:left-72" />

    @fluxScripts
</body>
</html>