<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = [];
        if (auth()->user()->isSystemAdmin()) {
            $projects = Project::all()->load(['trips', 'users']);
        } else {
            $projects = auth()->user()->projects->load(['trips', 'users']);
        }
        return Inertia::render('Projects', ['projects' => $projects]);
    }

    public function update(Request $request, Project $project)
    {
        $project->update($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]));

        return redirect()->back();
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->back();
    }
}
