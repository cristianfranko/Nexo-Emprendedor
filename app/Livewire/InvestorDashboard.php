<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InvestorDashboard extends Component
{
    // Propiedades para filtros de la galería
    public string $search = '';
    public string $selectedCategory = '';
    public string $sortBy = 'latest';
    public int $minFunding = 0;
    public int $maxFunding = 5000000;

    // --- Propiedades para las pestañas y el filtro de propuestas ---
    public string $activeTab = 'explorar';
    public string $proposalStatusFilter = '';

    public function render()
    {
        $user = Auth::user();

        // --- Lógica para la pestaña "EXPLORAR PROYECTOS" ---
        $projectsQuery = Project::query()
            ->with(['category', 'photos', 'entrepreneur'])
            ->withCount(['likes', 'investments']);
        
        if ($this->search) {
            $projectsQuery->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedCategory) {
            $projectsQuery->where('category_id', $this->selectedCategory);
        }
        $projectsQuery->whereBetween('funding_goal', [$this->minFunding, $this->maxFunding]);
        match ($this->sortBy) {
            'popular' => $projectsQuery->orderBy('likes_count', 'desc'),
            'nearing_funding' => $projectsQuery->orderByRaw('(SELECT SUM(proposed_amount) FROM investments WHERE investments.project_id = projects.id AND investments.status = \'negotiating\') / funding_goal DESC'),
            default => $projectsQuery->orderBy('created_at', 'desc'),
        };
        $projects = $projectsQuery->get();

        // --- Lógica para la pestaña "PROYECTOS EN SEGUIMIENTO" ---
        // Obtenemos los proyectos a los que el usuario ha dado "like"
        $watchlistProjects = $user->likes()->with(['category', 'photos', 'entrepreneur'])->get();

        // --- Lógica para la pestaña "MIS PROPUESTAS" ---
        $myProposalsQuery = $user->investments()->with('project')->latest();
        if ($this->proposalStatusFilter) {
            $myProposalsQuery->where('status', $this->proposalStatusFilter);
        }
        $myProposals = $myProposalsQuery->get();

        // --- Lógica para los KPIs ---
        $proposalsSent = $user->investments()->count();
        $activeNegotiations = $user->investments()->where('status', 'negotiating')->count();
        $watchlistCount = $watchlistProjects->count();

        return view('livewire.investor-dashboard', [
            'projects' => $projects,
            'watchlistProjects' => $watchlistProjects,
            'myProposals' => $myProposals,
            'categories' => Category::all(),
            // Pasamos los KPIs a la vista
            'proposalsSent' => $proposalsSent,
            'activeNegotiations' => $activeNegotiations,
            'watchlistCount' => $watchlistCount,
        ]);
    }
}