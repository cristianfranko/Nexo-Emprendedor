<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

<script>
    // Función centralizada para aplicar la clase 'dark' al <html>
    function applyThemeState(isDark) {
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    // Determina el tema inicial al cargar la página por primera vez
    const initialThemeIsDark = localStorage.theme === 'dark' || 
                               (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

    // Aplica el tema inmediatamente para evitar el "parpadeo" (FOUC)
    applyThemeState(initialThemeIsDark);

    // Evento para inicializar el store de Alpine.js UNA SOLA VEZ
    document.addEventListener('alpine:init', () => {
        Alpine.store('theme', {
            dark: document.documentElement.classList.contains('dark'),

            toggle() {
                this.dark = !this.dark;
                localStorage.theme = this.dark ? 'dark' : 'light';
                applyThemeState(this.dark);
            },
        });
    });

    // Escuchamos el evento de Livewire que se dispara DESPUÉS de cada navegación
    document.addEventListener('livewire:navigated', () => {
        // Releemos el estado desde localStorage y lo reaplicamos
        const themeIsDark = localStorage.theme === 'dark' || 
                            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
        applyThemeState(themeIsDark);
        
        // Sincronizamos el store de Alpine por si acaso
        if (Alpine.store('theme')) {
             Alpine.store('theme').dark = themeIsDark;
        }
    });
</script>