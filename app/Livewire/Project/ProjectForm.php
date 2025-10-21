<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectForm extends Component
{
    use WithFileUploads;

    public Project $project;

    // Propiedades del formulario
    public string $title = '';
    public string $description = '';
    public $category_id = '';
    public $funding_goal = '';
    public $min_investment = '';
    public string $business_model = '';
    public string $market_potential = '';
    public $deadline = '';

    // Propiedad para la subida de fotos
    public $photo;

    public function mount(Project $project)
    {
        $this->project = $project;
        
        if ($this->project->exists) {
            $this->title = $project->title;
            $this->description = $project->description;
            $this->category_id = $project->category_id;
            $this->funding_goal = $project->funding_goal;
            $this->min_investment = $project->min_investment;
            $this->business_model = $project->business_model;
            $this->market_potential = $project->market_potential;
            $this->deadline = $project->deadline ? $project->deadline->format('Y-m-d') : '';
        }
    }

    public function save()
    {
        if ($this->project->exists && $this->project->user_id !== Auth::id()) {
            abort(403);
        }

        $validatedData = $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'funding_goal' => 'required|numeric|min:0',
            'min_investment' => 'required|numeric|min:0',
            'business_model' => 'required|string',
            'market_potential' => 'required|string',
            'deadline' => 'nullable|date|after:today',
            'photo' => 'nullable|image|max:2048',
        ]);
        
        $projectData = collect($validatedData)->except('photo')->toArray();
        $projectData['user_id'] = Auth::id();

        if (empty($projectData['deadline'])) {
            $projectData['deadline'] = null;
        }
        
        $savedProject = Project::updateOrCreate(
            ['id' => $this->project->id],
            $projectData
        );

        if ($this->photo) {
            $path = $this->photo->store('project-photos', 'public');
            $savedProject->photos()->delete(); 
            $savedProject->photos()->create(['path' => $path]);
        }

        session()->flash('message', 'Proyecto guardado exitosamente.');
        return $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.project.project-form', [
            'categories' => Category::all()
        ]);
    }
}