<div>
    <h2 class="text-2xl font-bold mb-6">Panel de Inversor</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md border dark:border-zinc-700 hover:scale-[1.03] hover:shadow-xl dark:hover:border-blue-500 transition-all duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Propuestas Enviadas</h3>
                    <p class="text-3xl font-bold mt-1">{{ $proposalsSent }}</p>
                </div>
                <flux:icon.paper-airplane class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md border dark:border-zinc-700 hover:scale-[1.03] hover:shadow-xl dark:hover:border-blue-500 transition-all duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Negociaciones Activas</h3>
                    <p class="text-3xl font-bold mt-1">{{ $activeNegotiations }}</p>
                </div>
                <flux:icon.chat-bubble-left-right class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md border dark:border-zinc-700 hover:scale-[1.03] hover:shadow-xl dark:hover:border-blue-500 transition-all duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Proyectos en Seguimiento</h3>
                    <p class="text-3xl font-bold mt-1">{{ $watchlistCount }}</p>
                </div>
                <flux:icon.eye class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
            </div>
        </div>
    </div>

    <div x-data="{ activeTab: 'explorar' }">
        <div class="border-b border-zinc-200 dark:border-zinc-700 mb-6">
            <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                <button @click="activeTab = 'explorar'" :class="activeTab === 'explorar' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-200'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Explorar Proyectos</button>
                <button @click="activeTab = 'seguimiento'" :class="activeTab === 'seguimiento' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-200'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">En Seguimiento</button>
                <button @click="activeTab = 'propuestas'" :class="activeTab === 'propuestas' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-200'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Mis Propuestas</button>
            </nav>
        </div>

        <div x-show="activeTab === 'explorar'">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Buscar por palabra</label>
                    <input type="text" id="search" wire:model.live.debounce.300ms="search" placeholder="Ej: Billetera digital..." class="mt-1 block w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Categoría</label>
                    <select id="category" wire:model.live="selectedCategory" class="mt-1 block w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 rounded-md shadow-sm">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="sort" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Ordenar por</label>
                    <select id="sort" wire:model.live="sortBy" class="mt-1 block w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 rounded-md shadow-sm">
                        <option value="latest">Más Recientes</option>
                        <option value="popular">Más Populares</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($projects as $project)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden flex flex-col border dark:border-zinc-700 hover:scale-[1.03] hover:shadow-xl dark:hover:border-blue-500 transition-all duration-300 ease-in-out" wire:key="project-card-{{ $project->id }}">
                        <img src="{{ $project->photos->first() ? Storage::url($project->photos->first()->path) : 'https://via.placeholder.com/400x200.png/E2E8F0/4A5568?text=Sin+Imagen' }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">
                        <div class="p-4 flex-grow flex flex-col">
                            <h3 class="text-lg font-bold">{{ $project->title }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">{{ $project->category->name }}</p>
                            <p class="text-xs text-zinc-400 mb-3">Por: {{ $project->entrepreneur->name }}</p>
                            <div class="flex-grow"></div>
                            <div class="flex justify-end items-center text-sm space-x-4 mt-4">
                                <livewire:like-button :project="$project" wire:key="like-button-{{ $project->id }}" />
                            </div>
                        </div>
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-900/50 flex justify-end">
                            <a href="{{ route('project.view', $project) }}" wire:navigate class="text-sm font-medium text-blue-600 hover:underline">Ver Detalles</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12"><p class="text-zinc-500">No se encontraron proyectos.</p></div>
                @endforelse
            </div>
        </div>

        <div x-show="activeTab === 'seguimiento'" style="display: none;">
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($watchlistProjects as $project)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden" wire:key="watchlist-project-{{ $project->id }}">
                        <img src="{{ $project->photos->first() ? Storage::url($project->photos->first()->path) : 'https://via.placeholder.com/400x200.png/E2E8F0/4A5568?text=Sin+Imagen' }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-bold">{{ $project->title }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $project->category->name }}</p>
                            <div class="mt-4 flex justify-between items-center">
                                <livewire:like-button :project="$project" wire:key="watchlist-like-{{ $project->id }}" />
                                <a href="{{ route('project.view', $project) }}" wire:navigate class="text-sm font-medium text-blue-600 hover:underline">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-zinc-800 rounded-lg shadow-md">
                        <p class="text-zinc-500">Aún no sigues ningún proyecto.</p>
                        <p class="mt-2 text-sm">Usa el ícono de corazón <flux:icon.heart class="inline h-4 w-4 text-red-500" /> en los proyectos para añadirlos a tu lista.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div x-show="activeTab === 'propuestas'" style="display: none;">
            <div class="mb-4 max-w-xs">
                <label for="proposal_status" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Filtrar por estado</label>
                <select id="proposal_status" wire:model.live="proposalStatusFilter" class="mt-1 block w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 rounded-md shadow-sm">
                    <option value="">Todos</option>
                    <option value="pending">Pendientes</option>
                    <option value="negotiating">En Negociación</option>
                    <option value="rejected">Rechazadas</option>
                </select>
            </div>
            <div class="space-y-4">
                @forelse($myProposals as $proposal)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-4 flex justify-between items-center">
                        <div>
                            <a href="{{ route('project.view', $proposal->project) }}" wire:navigate class="text-lg font-bold text-indigo-600 dark:text-indigo-400 hover:underline">{{ $proposal->project->title }}</a>
                            <p class="text-sm mt-1">Monto propuesto: ${{ number_format($proposal->proposed_amount, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <flux:badge :color="$proposal->status === 'pending' ? 'yellow' : ($proposal->status === 'negotiating' ? 'blue' : 'red')">{{ ucfirst($proposal->status) }}</flux:badge>
                            @if($proposal->status === 'negotiating')
                                <a href="{{ route('conversation.show', $proposal) }}" wire:navigate class="mt-2 inline-block text-sm text-blue-500 hover:underline">Ir a la Conversación</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-zinc-800 rounded-lg shadow-md"><p class="text-zinc-500">No tienes propuestas con este estado.</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>