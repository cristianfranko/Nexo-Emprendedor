<div 
    class="fixed bottom-6 left-6 z-50 {{ $desktopPositionClass }}"
    x-data="{
        fontSize: 1,
        grayscale: false,
        reducedMotion: false,
        isSpeaking: false,

        init() {
            this.loadSettings();
            this.applySettings();
        },
        loadSettings() {
            const saved = localStorage.getItem('accesibilidad');
            if (saved) {
                const s = JSON.parse(saved);
                this.fontSize = s.fontSize || 1;
                this.grayscale = s.grayscale || false;
                this.reducedMotion = s.reducedMotion || false;
            }
        },
        saveSettings() {
            localStorage.setItem('accesibilidad', JSON.stringify({
                fontSize: this.fontSize,
                grayscale: this.grayscale,
                reducedMotion: this.reducedMotion
            }));
        },
        applySettings() {
            // Apuntamos a document.documentElement (<html>) para el tamaño de fuente.
            // Esto afectará a todas las unidades 'rem' de la página de manera consistente.
            document.documentElement.style.fontSize = (16 * this.fontSize) + 'px';

            // Apuntamos a document.documentElement para el filtro de escala de grises.
            // Esto asegura que TODA la página se vea afectada, sin importar el layout.
            // El widget en sí no se verá afectado porque usa `position: fixed`.
            document.documentElement.style.filter = this.grayscale ? 'grayscale(100%)' : 'none';
            
            // La lógica para el movimiento reducido se mantiene en el body.
            document.body.style.scrollBehavior = this.reducedMotion ? 'auto' : 'smooth';
            const video = document.querySelector('video');
            if (video) {
                this.reducedMotion ? video.pause() : video.play().catch(e => {});
            }
            this.saveSettings();
        },
        increaseFont() {
            if (this.fontSize < 2) { this.fontSize += 0.2; this.applySettings(); }
        },
        decreaseFont() {
            if (this.fontSize > 0.8) { this.fontSize -= 0.2; this.applySettings(); }
        },
        toggleGrayscale() {
            this.grayscale = !this.grayscale; this.applySettings();
        },
        toggleReducedMotion() {
            this.reducedMotion = !this.reducedMotion; this.applySettings();
        },
        speakText() {
            if (!('speechSynthesis' in window)) return;
            if (this.isSpeaking) {
                speechSynthesis.cancel();
                this.isSpeaking = false;
                return;
            }
            // Obtenemos el texto del slot principal del layout de la app
            const mainContent = document.querySelector('main');
            const text = mainContent ? mainContent.innerText : document.body.innerText;
            const msg = new SpeechSynthesisUtterance(text.substring(0, 1000));
            msg.lang = 'es-AR';
            msg.onend = () => this.isSpeaking = false;
            msg.onerror = () => this.isSpeaking = false;
            speechSynthesis.speak(msg);
            this.isSpeaking = true;
        },
        resetAll() {
            this.fontSize = 1; this.grayscale = false; this.reducedMotion = false;
            // CAMBIO 3: Asegurarse de que el filtro también se limpie al resetear.
            document.documentElement.style.filter = 'none';
            this.applySettings();
            localStorage.removeItem('accesibilidad');
            if (Alpine.store('theme').dark) {
                Alpine.store('theme').toggle();
            }
        }
    }"
>

    <button @click="$refs.panel.classList.toggle('hidden')" class="bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition flex items-center justify-center" aria-label="Abrir panel de accesibilidad">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/></svg>
    </button>
    
    <div x-ref="panel" class="hidden absolute bottom-0 mb-16 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 w-64 border dark:border-gray-700">
        <!-- Contenido del panel -->
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-bold text-gray-800 dark:text-white">Accesibilidad</h3>
            <button @click="$refs.panel.classList.add('hidden')" class="text-gray-500 hover:text-gray-700 text-lg">✕</button>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tamaño de texto</label>
            <div class="flex space-x-2">
                <button @click="decreaseFont()" class="flex-1 bg-gray-200 dark:bg-gray-700 py-1 rounded text-sm">A−</button>
                <button @click="increaseFont()" class="flex-1 bg-gray-200 dark:bg-gray-700 py-1 rounded text-sm">A+</button>
            </div>
        </div>
        <div class="mb-3"><button @click="toggleGrayscale()" class="w-full text-left px-3 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm">Blanco y negro</button></div>
        <div class="mb-3"><button @click="toggleReducedMotion()" class="w-full text-left px-3 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm"><span x-text="reducedMotion ? 'Activar animaciones' : 'Sin animaciones'"></span></button></div>
        <div class="mb-3"><button @click="speakText()" class="w-full text-left px-3 py-2 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 text-sm font-medium">🎧 <span x-text="isSpeaking ? 'Detener lectura' : 'Leer en voz alta'"></span></button></div>
        <div class="mb-3"><button @click="$store.theme.toggle()" class="w-full text-left px-3 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm">Modo nocturno</button></div>
        <button @click="resetAll()" class="w-full mt-2 px-3 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 text-sm rounded">Restablecer todo</button>
    </div>
</div>