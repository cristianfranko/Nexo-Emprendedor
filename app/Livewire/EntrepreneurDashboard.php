<?php

namespace App\Livewire;

use App\Models\Investment;
use App\Models\Notification;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class EntrepreneurDashboard extends Component
{
    public function delete(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }

        foreach ($project->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        $project->delete();
        
        session()->flash('message', 'Proyecto eliminado exitosamente.');
    }

    public function acceptProposal(Investment $investment)
    {
        if ($investment->project->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $investment->status = 'negotiating';
        $investment->save();

        Notification::create([
            'user_id' => $investment->investor_id,
            'message' => "¡Buenas noticias! Tu propuesta para '{$investment->project->title}' ha sido aceptada. Ya puedes iniciar la conversación.",
            'link' => route('conversation.show', $investment),
        ]);

        session()->flash('message', '¡Propuesta aceptada! Ahora puedes comunicarte con el inversor.');
    }

    public function rejectProposal(Investment $investment)
    {
        if ($investment->project->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $investment->status = 'rejected';
        $investment->save();
        
        session()->flash('message', 'La propuesta ha sido rechazada.');
    }


    public function render()
    {
        $user = Auth::user();

        $projects = $user->projects()
                         ->with('category')
                         ->withCount(['likes', 'investments'])
                         ->latest()
                         ->get();

        $proposals = $user->proposals()
                          ->with(['project', 'investor'])
                          ->latest()
                          ->get();

        $totalProjects = $projects->count();
        $totalLikes = $projects->sum('likes_count');
        $totalProposals = $proposals->count();

        return view('livewire.entrepreneur-dashboard', [
            'projects' => $projects,
            'proposals' => $proposals,
            'totalProjects' => $totalProjects,
            'totalLikes' => $totalLikes,
            'totalProposals' => $totalProposals,
        ]);

    }
}