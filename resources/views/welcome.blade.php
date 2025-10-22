<x-layouts.guest>
    <!-- Hero -->
    <section class="relative text-center py-16 px-6 min-h-[60vh] flex flex-col justify-center items-center overflow-hidden">
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0">
            <source src="{{ asset('images/video.mp4') }}" type="video/mp4">Tu navegador no soporta videos.
        </video>
        <div class="absolute inset-0 bg-white/40 z-10"></div>
        <div class="relative z-20 max-w-3xl">
            <img src="{{ asset('images/nexo.png') }}" alt="Logo" class="w-80 h-40 mx-auto drop-shadow-[0_0_14px_rgba(255,255,255,1.8)] animate-fade-in-up" />
            <h1 class="text-3xl md:text-5xl font-bold mb-4 text-black">Conectando Emprendedores e Inversores en la Zona NEA 🇦🇷</h1>
            <p class="max-w-4xl mx-auto text-white mb-6 drop-shadow-lg">Un ecosistema digital donde las ideas innovadoras encuentran el apoyo que necesitan para crecer.</p>
            @guest
            <a href="{{ route('register') }}" wire:navigate class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg text-lg hover:bg-blue-700 transition">Comenzar Ahora</a>
            @endguest
        </div>
    </section>

    <!-- Cómo Funciona -->
    <section class="bg-white dark:bg-gray-900 py-16 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white sm:text-4xl">
                Un Proceso Simple para un Gran Impacto
            </h2>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                Conectar talento e inversión nunca fue tan fácil. Sigue estos tres pasos para comenzar.
            </p>

            <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Paso 1: Regístrate -->
                <div class="p-8">
                    <div class="flex items-center justify-center h-16 w-16 mx-auto bg-blue-100 dark:bg-blue-900/50 rounded-full">
                        <x-icons.user-plus class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h3 class="mt-6 text-xl font-bold text-gray-800 dark:text-white">Crea tu Perfil</h3>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-300">
                        Elige si eres un emprendedor con una idea brillante o un inversor buscando la próxima gran oportunidad. El registro es rápido y seguro.
                    </p>
                </div>

                <!-- Paso 2: Publica o Descubre -->
                <div class="p-8">
                    <div class="flex items-center justify-center h-16 w-16 mx-auto bg-blue-100 dark:bg-blue-900/50 rounded-full">
                        <x-icons.upload-search class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h3 class="mt-6 text-xl font-bold text-gray-800 dark:text-white">Publica o Descubre</h3>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-300">
                        Los emprendedores presentan sus proyectos, detallando su visión y metas. Los inversores exploran una galería de oportunidades.
                    </p>
                </div>

                <!-- Paso 3: Conecta e Invierte -->
                <div class="p-8">
                    <div class="flex items-center justify-center h-16 w-16 mx-auto bg-blue-100 dark:bg-blue-900/50 rounded-full">
                        <x-icons.chart-trending-up class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h3 class="mt-6 text-xl font-bold text-gray-800 dark:text-white">Impulsa el Crecimiento</h3>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-300">
                        Inicia conversaciones, negocia propuestas y cierra acuerdos. Juntos, damos vida a los proyectos que transformarán la economía de la región.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Componentes Livewire -->
    <section>@livewire('mostrar-proyectos')</section>
    <section>@livewire('cotizaciones')</section>
    <section>@livewire('noticias-economia')</section>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" data-navigate-once></script>

    {{-- 2. Nuestro script de inicialización. --}}
    <script>
        function initSwiper() {
            if (typeof Swiper === 'undefined') {
                return;
            }

            const swiperEl = document.querySelector('.mySwiper');
            if (swiperEl && swiperEl.swiper) {
                swiperEl.swiper.destroy(true, true);
            }

            const slides = document.querySelectorAll('.mySwiper .swiper-slide');
            if (slides.length === 0) return;

            new Swiper('.mySwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: slides.length > 3,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
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

        document.addEventListener('livewire:navigated', function() {
            setTimeout(initSwiper, 50);
        });
    </script>
    @endpush
</x-layouts.guest>