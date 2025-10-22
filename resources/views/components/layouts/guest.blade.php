<!DOCTYPE html>
<html lang="es" class="scroll-smooth" x-data x-bind:class="$store.theme.dark ? 'dark' : ''">
<head>
    @include('partials.head')
</head>
<body class="bg-white text-gray-800 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

<div id="main-content">
    <!-- Navbar -->
    <header class="flex justify-between items-center px-6 py-4 shadow-sm dark:shadow-gray-800">
        <a href="{{ route('home') }}" wire:navigate>
            <img 
                src="{{ asset('images/nexo.png') }}" 
                alt="Logo" 
                class="w-40 h-20 dark:drop-shadow-[0_0_14px_rgba(255,255,255,1.95)] transition duration-300"
            />
        </a>

        <nav class="flex items-center space-x-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm hover:underline">Dashboard</a>
            @else
                {{-- Solo mostrar botón de Iniciar Sesión si NO estamos en la página de login --}}
                @if (!request()->routeIs('login'))
                    <a href="{{ route('login') }}" wire:navigate class="px-4 py-2 text-sm rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition">Iniciar Sesión</a>
                @endif

                {{-- Solo mostrar botón de Registrarse si NO estamos en la página de registro --}}
                @if (!request()->routeIs('register'))
                    <a href="{{ route('register') }}" wire:navigate class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">Registrarse</a>
                @endif
            @endauth

            <button 
                @click="$store.theme.toggle()"
                class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition"
                aria-label="Alternar modo oscuro"
            >
                <svg x-show="!$store.theme.dark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a.75.75 0 01.75.75V4a.75.75 0 01-1.5 0V2.75A.75.75 0 0110 2zM10 16a.75.75 0 01.75.75V18a.75.75 0 01-1.5 0v-1.25A.75.75 0 0110 16zM4 9.25a.75.75 0 000 1.5H2.75a.75.75 0 000-1.5H4zM17.25 9.25a.75.75 0 000 1.5H19a.75.75 0 000-1.5h-1.75zM4.22 4.22a.75.75 0 011.06 0L6.5 5.44a.75.75 0 11-1.06 1.06L4.22 5.28a.75.75 0 010-1.06zM14.56 14.56a.75.75 0 011.06 0l1.22 1.22a.75.75 0 01-1.06 1.06l-1.22-1.22a.75.75 0 010-1.06zM14.56 5.44a.75.75 0 010-1.06l1.22-1.22a.75.75 0 111.06 1.06L15.62 5.44a.75.75 0 01-1.06 0zM4.22 15.78a.75.75 0 010-1.06l1.22-1.22a.75.75 0 111.06 1.06L5.28 15.78a.75.75 0 01-1.06 0z"></path>
                </svg>
                <svg x-show="$store.theme.dark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707 8 8 0 1017.293 13.293z"></path>
                </svg>
            </button>
        </nav>
    </header>

    {{-- El contenido específico de cada página irá aquí --}}
    {{ $slot }}

    <footer class="text-center py-6 text-sm border-t dark:border-gray-700">
        © {{ date('Y') }} Zona NEA – Formosa. Todos los derechos reservados.
    </footer>
</div>
   
@livewire('accesibilidad-widget')

@livewireScripts
{{-- La pila de scripts para Swiper y otros --}}
@stack('scripts')

</body>
</html>