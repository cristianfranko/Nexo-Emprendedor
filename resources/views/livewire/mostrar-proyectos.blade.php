<div class="px-6 py-12 max-w-7xl mx-auto">
    <h2 class="text-2xl font-semibold mb-6 text-center">🌱 Proyectos Destacados</h2>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @forelse($proyectos as $proyecto)
                <div class="swiper-slide">
                    {{-- CAMBIO 1: Añadimos wire:click para abrir el modal y cursor-pointer --}}
                    <div wire:click="showProjectDetails({{ $proyecto->id }})" class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-transparent hover:shadow-xl hover:scale-[1.03] dark:hover:border-blue-500 transition-all duration-300 ease-in-out overflow-hidden h-full flex flex-col cursor-pointer">
                        @if ($proyecto->photos->isNotEmpty())
                            <img 
                                src="{{ Storage::url($proyecto->photos->first()->path) }}" 
                                alt="{{ $proyecto->title }}" 
                                class="w-full h-48 object-cover"
                            >
                        @else
                            <div class="w-full h-48 bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                <span class="text-zinc-500">Sin Imagen</span>
                            </div>
                        @endif

                        <div class="p-4 flex-grow">
                            <h3 class="font-bold text-lg mb-2">{{ $proyecto->title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm">{{ Str::limit($proyecto->description, 100) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide">
                    <p class="text-center text-zinc-500 col-span-full">No hay proyectos disponibles en este momento.</p>
                </div>
            @endforelse
        </div>

        <div class="swiper-button-next !text-blue-600 dark:!text-blue-400"></div>
        <div class="swiper-button-prev !text-blue-600 dark:!text-blue-400"></div>
        <div class="swiper-pagination !bottom-0 mt-4"></div>
    </div>

    {{-- CAMBIO 2: Añadimos el modal --}}
    @if ($showModal && $selectedProject)
    <div 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
        x-data="{ show: @entangle('showModal') }"
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="$wire.closeModal()"
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col" @click.away="$wire.closeModal()">
            <!-- Header del Modal -->
            <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                <h3 class="text-xl font-bold">{{ $selectedProject->title }}</h3>
                <button wire:click="closeModal" class="text-gray-500 hover:text-gray-800 dark:hover:text-white">&times;</button>
            </div>

            <!-- Contenido del Modal (con scroll) -->
            <div class="p-6 overflow-y-auto">
                @if ($selectedProject->photos->isNotEmpty())
                    <img src="{{ Storage::url($selectedProject->photos->first()->path) }}" alt="{{ $selectedProject->title }}" class="w-full h-64 object-cover rounded-lg mb-4">
                @endif
                
                <p class="text-indigo-600 dark:text-indigo-400 font-semibold mb-4">{{ $selectedProject->category->name }}</p>

                <div class="grid grid-cols-2 gap-4 text-center mb-6">
                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Meta de Financiación</p>
                        <p class="text-xl font-bold">${{ number_format($selectedProject->funding_goal, 0) }}</p>
                    </div>
                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Inversión Mínima</p>
                        <p class="text-xl font-bold">${{ number_format($selectedProject->min_investment, 0) }}</p>
                    </div>
                </div>

                <h4 class="font-semibold mb-2">Descripción del Proyecto</h4>
                <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $selectedProject->description }}</p>
            </div>

            <!-- Footer del Modal (Call to Action) -->
            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t dark:border-gray-700 text-center">
                @guest
                    <p class="text-sm mb-2">¿Te interesa este proyecto?</p>
                    <a href="{{ route('register') }}" wire:navigate class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Regístrate para Invertir</a>
                @endguest

                @auth
                    @if(Auth::user()->role === 'investor')
                        <p class="text-sm mb-2">Da el siguiente paso y apoya esta idea.</p>
                        {{-- Este botón despacha el evento que abre el otro modal para proponer --}}
                        <button 
                            wire:click="$dispatch('open-proposal-modal', { projectId: {{ $selectedProject->id }} })"
                            class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        >
                            Proponer Inversión
                        </button>
                    @else
                        <p class="text-sm text-gray-500">Inicia sesión como inversor para poder proponer.</p>
                    @endif
                @endauth
            </div>
        </div>
    </div>
    @endif

    {{-- Esto asegura que el modal de propuesta de inversión siga funcionando --}}
    @if(Auth::check() && Auth::user()->role === 'investor')
        <livewire:investment.proposal-modal />
    @endif

    {{-- El script de Swiper no necesita cambios --}}
    <script>
        // ... (tu script de Swiper se mantiene igual)
    </script>
</div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        let swiperInstance = null;

        function initSwiper() {
            // Destruir la instancia anterior si existe
            if (swiperInstance !== null) {
                swiperInstance.destroy(true, true);
                swiperInstance = null;
            }

            const slides = document.querySelectorAll('.mySwiper .swiper-slide');
            if (slides.length === 0) return;

            swiperInstance = new Swiper('.mySwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: slides.length > 3,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    },
                },
            });
        }

        // Inicializar al cargar la página
        document.addEventListener('DOMContentLoaded', initSwiper);

        // Reinicializar cuando Livewire actualice el DOM
        document.addEventListener('livewire:navigated', initSwiper);
        document.addEventListener('livewire:updated', initSwiper);
    </script>
</div>