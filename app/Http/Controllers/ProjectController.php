<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $project = Project::create([
            'name' => $request->name
        ]);

        return redirect()->route('tasks.index');
    }
}
