<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
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

        return redirect()->back()->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->back()->with('success', 'Project deleted.');
    }

    public function removeUser(Request $request, Project $project, User $user)
    {
        if (auth()->user()->id == $user->id) {
            return redirect()->back()->with('error', 'You can\'t remove yourself from the project.');
        } else if (auth()->user()->isProjectAdmin() && $user->isProjectAdmin()) {
            return redirect()->back()->with('error', 'You can\'t remove a project admin from the project.');
        }
        $project->users()->detach($user);
        return redirect()->back()->with('success', 'User removed from project.');
    }
}
