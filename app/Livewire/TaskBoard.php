<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskBoard extends Component
{
    public function render()
    {
        $currentTeamId = session('current_team_id', auth()->id());

        $todoTasks = Task::where('team_id', $currentTeamId)->where('status', 'todo')->get();
        $doneTasks = Task::where('team_id', $currentTeamId)->where('status', 'done')->get();

        return view('livewire.task-board', [
            'todoTasks' => $todoTasks,
            'doneTasks' => $doneTasks,
        ]);
    }
}