<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Crea una cuenta')" :description="__('Ingresa tus datos para crear tu cuenta')" />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <div x-data="faceRegister()">
        <form wire:submit="register" class="flex flex-col gap-6">
            <flux:input wire:model="name" :label="__('Nombre')" type="text" required autofocus autocomplete="name" :placeholder="__('Nombre completo')" />
            <flux:input wire:model="email" :label="__('Correo Electrónico')" type="email" required autocomplete="email" placeholder="email@ejemplo.com" />
            <flux:input wire:model="password" :label="__('Contraseña')" type="password" required autocomplete="new-password" :placeholder="__('Contraseña')" viewable />
            <flux:input wire:model="password_confirmation" :label="__('Confirmar Contraseña')" type="password" required autocomplete="new-password" :placeholder="__('Confirmar Contraseña')" viewable />
            
            <div class="grid gap-2">
                <label for="role" class="font-medium text-sm text-zinc-700 dark:text-zinc-300">{{ __('Soy un...') }}</label>
                <select wire:model="role" id="role" class="block w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="" disabled>{{ __('Selecciona tu rol') }}</option>
                    <option value="entrepreneur">{{ __('Emprendedor') }}</option>
                    <option value="investor">{{ __('Inversor') }}</option>
                </select>
                @error('role') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mt-2 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <flux:subheading>{{ __('(Opcional) Añade tu rostro para un inicio de sesión rápido') }}</flux:subheading>
                
                <div class="mt-2">
                    <div x-show="cameraOpen" style="display: none;" class="relative w-full h-48 bg-zinc-200 dark:bg-zinc-700 rounded-lg overflow-hidden">
                        <video x-ref="video" class="w-full h-full object-cover" autoplay playsinline></video>
                        <canvas x-ref="canvas" class="hidden"></canvas>
                    </div>
                    <div class="mt-2 flex gap-4">
                        <flux:button x-show="!cameraOpen && !faceImageCaptured" @click="startCamera()" type="button" variant="outline">{{ __('Añadir Rostro') }}</flux:button>
                        <flux:button x-show="cameraOpen" style="display: none;" @click="captureAndSetImageData()" x-bind:disabled="loading" type="button">
                            <span x-show="!loading">{{ __('Capturar Foto') }}</span>
                            <span x-show="loading">{{ __('Procesando...') }}</span>
                        </flux:button>
                        <flux:button x-show="cameraOpen" style="display: none;" @click="stopCamera()" variant="outline" type="button">{{ __('Cancelar') }}</flux:button>
                    </div>
                    <p x-text="message" class="mt-2 text-sm" :class="{ 'text-green-600 dark:text-green-400': success, 'text-red-600 dark:text-red-400': !success }"></p>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <flux:button type="submit" variant="primary" class="w-full">{{ __('Crear cuenta') }}</flux:button>
            </div>
        </form>
    </div>

    <script>
        function faceRegister() {
            return {
                cameraOpen: false, loading: false, message: '', success: false, stream: null, faceImageCaptured: false,
                startCamera() {
                    this.message = ''; this.cameraOpen = true;
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(stream => { this.stream = stream; this.$refs.video.srcObject = stream; })
                        .catch(err => {
                            this.message = 'No se pudo acceder a la cámara. Revisa los permisos.';
                            this.success = false; this.cameraOpen = false;
                        });
                },
                stopCamera() {
                    this.cameraOpen = false;
                    if (this.stream) { this.stream.getTracks().forEach(track => track.stop()); }
                },
                captureAndSetImageData() {
                    this.loading = true; this.message = '';
                    const video = this.$refs.video; const canvas = this.$refs.canvas;
                    canvas.width = video.videoWidth; canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageDataUrl = canvas.toDataURL('image/jpeg');

                    @this.set('faceImage', imageDataUrl);
                    
                    this.loading = false;
                    this.success = true;
                    this.faceImageCaptured = true;
                    this.message = '¡Rostro capturado! Puedes terminar tu registro.';
                    this.stopCamera();
                }
            }
        }
    </script>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('¿Ya tienes una cuenta?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Inicia Sesión') }}</flux:link>
    </div>
</div>