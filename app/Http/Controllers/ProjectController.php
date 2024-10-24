<?php

namespace App\Http\Controllers;

use App\Models\CarType;
use App\Models\Project;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $tenant_id = session('tenant_id');
        $tenant = Tenant::find($tenant_id);
        $projects = [];
        if (auth()->user()->isAdminInTenant($tenant_id)) {
            $projects = $tenant->projects->load(['trips', 'users']);
        } else {
            $projects = auth()->user()->adminProjects->load(['trips', 'users']);
        }
        return Inertia::render('Projects', ['projects' => $projects]);
    }

    public function create()
    {
        $carTypes = CarType::all();
        return Inertia::render('Projects/Create', ['carTypes' => $carTypes]);
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
            return redirect()->back()->with('error', 'You can\'t remove a admin from the project.');
        }
        $project->users()->detach($user);
        return redirect()->back()->with('success', 'User removed from project.');
    }
}
