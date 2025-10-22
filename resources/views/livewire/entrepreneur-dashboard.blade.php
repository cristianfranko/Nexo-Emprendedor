<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Panel de Emprendedor</h2>
        <a href="{{ route('project.create') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
            Crear Nuevo Proyecto
        </a>
    </div>

    @if (session()->has('message'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flex justify-between items-center" 
            role="alert"
        >
            <span class="block sm:inline">{{ session('message') }}</span>
            <button @click="show = false" class="ml-4">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Cerrar</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md border dark:border-zinc-700 hover:scale-[1.03] hover:shadow-xl dark:hover:border-blue-500 transition-all duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Proyectos Totales</h3>
                    <p class="text-3xl font-bold mt-1 text-left">{{ $totalProjects }}</p>
                </div>
                <flux:icon.folder-git-2 class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md border dark:border-zinc-700 hover:scale-[1.03] hover:shadow-xl dark:hover:border-blue-500 transition-all duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Propuestas Recibidas</h3>
                    <p class="text-3xl font-bold mt-1 text-left">{{ $totalProposals }}</p>
                </div>
                <flux:icon.inbox class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md border dark:border-zinc-700 hover:scale-[1.03] hover:shadow-xl dark:hover:border-blue-500 transition-all duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">"Me Gusta" Totales</h3>
                    <p class="text-3xl font-bold mt-1 text-left">{{ $totalLikes }}</p>
                </div>
                <flux:icon.heart class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
            </div>
        </div>
    </div>

    <div x-data="{ activeTab: 'proyectos' }">
        <div class="border-b border-zinc-200 dark:border-zinc-700 mb-6">
            <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                <button @click="activeTab = 'proyectos'"
                        :class="activeTab === 'proyectos' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:hover:text-zinc-200'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Mis Proyectos
                </button>
                <button @click="activeTab = 'propuestas'"
                        :class="activeTab === 'propuestas' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:hover:text-zinc-200'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Propuestas Recibidas
                </button>
            </nav>
        </div>

        <div x-show="activeTab === 'proyectos'" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse ($projects as $project)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden flex flex-col 
                                border dark:border-zinc-700 hover:scale-[1.03] hover:shadow-xl dark:hover:border-blue-500 
                                transition-all duration-300 ease-in-out"
                         wire:key="'project-card-' . $project->id">
                        <img src="{{ $project->photos->first() ? Storage::url($project->photos->first()->path) : 'https://via.placeholder.com/400x200.png/E2E8F0/4A5568?text=Sin+Imagen' }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">
                        <div class="p-4 flex-grow">
                            <h3 class="text-lg font-bold">{{ $project->title }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3">{{ $project->category->name }}</p>
                            <div class="mb-3">
                                <label class="text-xs font-semibold">Progreso de Financiación</label>
                                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5">
                                    <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $project->getFundingProgress() }}%"></div>
                                </div>
                                <p class="text-xs text-right mt-1">{{ $project->getFundingProgress() }}% de ${{ number_format($project->funding_goal, 0) }}</p>
                            </div>
                            <div class="flex justify-around items-center text-sm border-t border-b dark:border-zinc-700 py-2 my-3">
                                <div class="text-center">
                                    <span class="font-bold block">{{ $project->investments_count }}</span>
                                    <span class="text-zinc-500">Propuestas</span>
                                </div>
                                <div class="text-center">
                                    <span class="font-bold block">{{ $project->likes_count }}</span>
                                    <span class="text-zinc-500">Me Gusta</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-900/50 flex justify-end gap-2">
                            <a href="{{ route('project.edit', $project) }}" wire:navigate class="text-sm text-blue-500 hover:underline">Editar</a>
                            <button wire:click="delete({{ $project->id }})" wire:confirm="¿Estás seguro?" class="text-sm text-red-500 hover:underline">Eliminar</button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 text-center py-12 bg-white dark:bg-zinc-800 rounded-lg shadow-md">
                        <p class="text-zinc-500">Aún no has creado ningún proyecto.</p>
                        <p class="mt-2 text-sm">¡Anímate a empezar y comparte tu idea!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div x-show="activeTab === 'propuestas'" style="display: none;">
            <div class="space-y-6">
                 @forelse ($proposals as $proposal)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Propuesta para tu proyecto:</p>
                                <h3 class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ $proposal->project->title }}</h3>
                                <p class="mt-2 text-sm"><span class="font-semibold">Inversor:</span> {{ $proposal->investor->name }}</p>
                                <p class="text-sm"><span class="font-semibold">Monto Propuesto:</span> ${{ number_format($proposal->proposed_amount, 2) }}</p>
                            </div>
                            <div>
                                <flux:badge :color="$proposal->status === 'pending' ? 'yellow' : ($proposal->status === 'negotiating' ? 'blue' : ($proposal->status === 'rejected' ? 'red' : 'gray'))">
                                    {{ ucfirst($proposal->status) }}
                                </flux:badge>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t dark:border-zinc-700">
                            <p class="text-sm font-semibold mb-2">Mensaje del Inversor:</p>
                            <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-zinc-700 p-3 rounded-md">{{ $proposal->message }}</p>
                        </div>
                        <div class="mt-6 flex justify-end gap-4">
                            @if ($proposal->status === 'pending')
                                <flux:button variant="danger" wire:click="rejectProposal({{ $proposal->id }})" wire:confirm="¿Estás seguro?">Rechazar</flux:button>
                                <flux:button variant="primary" wire:click="acceptProposal({{ $proposal->id }})">Aceptar Contacto</flux:button>
                            @elseif ($proposal->status === 'negotiating')
                                <a href="{{ route('conversation.show', $proposal) }}" wire:navigate class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Iniciar Conversación</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400">Aún no has recibido ninguna propuesta de inversión.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>