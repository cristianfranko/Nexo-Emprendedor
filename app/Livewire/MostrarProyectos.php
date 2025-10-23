<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class MostrarProyectos extends Component
{
    public $showModal = false;
    public ?Project $selectedProject = null;

    public function showProjectDetails($projectId)
    {
        $this->selectedProject = Project::with('category', 'photos')->find($projectId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedProject = null;

        $this->dispatch('modal-closed-reinit-swiper');
    }

    public function render()
    {
        $proyectos = Project::with('photos')
                            ->withCount('likes')
                            ->orderBy('likes_count', 'desc')
                            ->take(6)
                            ->get();

        return view('livewire.mostrar-proyectos', [
            'proyectos' => $proyectos
        ]);
    }
}
