<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function index(?Task $task = null): View
    {
        return view('task', [
            'task' => $task,
        ]);
    }

  
    public function createOrUpdate(Request $request): RedirectResponse
    {
        $request -> validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task = Task::updateOrCreate(
            ['id' => $request -> route('task')],
            [
                'title' => $request -> title,
                'description' => $request -> description,
                'status' => $request -> status,
                'user_id' => Auth::id(),
            ]
        );

        return redirect()
            -> route('tasks.index', ['task' => $task]);
    }

    public function delete(Task $task): RedirectResponse
    {
        $task -> delete();

        return redirect()
            -> route('index');
    }
    
}
