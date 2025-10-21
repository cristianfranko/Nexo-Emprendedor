<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class MostrarProyectos extends Component
{
    public function render()
    {
        // Consulta la base de datos para obtener los proyectos.
        $proyectos = Project::with('photos') // Carga las fotos para eficiencia
                            ->withCount('likes')      // Crea una nueva columna virtual 'likes_count'
                            ->orderBy('likes_count', 'desc') // Ordena por la cantidad de likes de mayor a menor
                            ->take(6)                 // Limita a los 6 proyectos más populares
                            ->get();

        return view('livewire.mostrar-proyectos', [
            'proyectos' => $proyectos
        ]);
    }
}