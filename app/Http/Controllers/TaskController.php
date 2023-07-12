<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

class TaskController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        $tasks = Task::with('project')->orderBy('priority')->get();
        $editMode = false;


        return view('tasks.index', compact('projects', 'tasks', 'editMode'));
    }

    public function create()
    {
        $projects = Project::all();

        return view('tasks.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'priority' => 'required|integer',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $task = Task::create([
            'name' => $request->name,
            'priority' => $request->priority,
            'project_id' => $request->project_id,
        ]);

        return redirect()->route('tasks.index');
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $tasks = Task::with('project')->orderBy('priority')->get();
        $editMode = true;


        return view('tasks.index', compact('projects', 'tasks', 'editMode','task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required',
            'priority' => 'required|integer',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $task->update([
            'name' => $request->name,
            'priority' => $request->priority,
            'project_id' => $request->project_id,
        ]);

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index');
    }

    public function reorder(Request $request)
    {
        $taskIds = $request->input('taskIds');

        foreach ($taskIds as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function filterByProject(Project $project)
    {
        $projects = Project::all();
        $tasks = Task::with('project')
            ->where('project_id', $project->id)
            ->orderBy('priority')
            ->get();
        $editMode = false;
        return view('tasks.index', compact('projects', 'tasks', 'project', 'editMode'));
    }
    
}
