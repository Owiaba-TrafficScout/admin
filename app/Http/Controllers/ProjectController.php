<?php

namespace App\Http\Controllers;

use App\Models\CarType;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Role;
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
        $roles = Role::all();
        $projects = [];
        if (auth()->user()->isAdminInTenant($tenant_id)) {
            $projects = $tenant->projects->load(['trips', 'users.pivot.role']);
        } else {
            $projects = auth()->user()->adminProjects->load(['trips', 'users.pivot.role']);
        }

        return Inertia::render('Projects', ['projects' => $projects, 'roles' => $roles]);
    }

    public function create()
    {
        if (!auth()->user()->isAdminInTenant()) {
            return redirect()->back()->with('error', 'You are not allowed to create a project.');
        }
        $carTypes = CarType::orderByDesc('created_at')->get();
        return Inertia::render('Projects/Create', ['carTypes' => $carTypes]);
    }


    public function store(Request $request)
    {
        if (!auth()->user()->isAdminInTenant()) {
            return redirect()->back()->with('error', 'You are not allowed to create a project.');
        }
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        $attributes['tenant_id'] = session('tenant_id');
        $project = Project::create($attributes);

        return redirect()->route('projects.index')->with('success', 'Project created.');
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
        $projectUser = $project->users()->where('user_id', $user->id)->first();
        $authProjectUser = $project->users()->where('user_id', auth()->user()->id)->first();
        if (auth()->user()->id == $user->id) {
            return redirect()->back()->with('error', 'You can\'t remove yourself from the project.');
        } else if ($authProjectUser?->isProjectAdmin()) {
            if ($projectUser->isProjectAdmin()) {
                return redirect()->back()->with('error', 'You can\'t remove an admin from the project.');
            }
        } else if (!auth()->user()->isAdminInTenant()) {
            return redirect()->back()->with('error', 'You are not allowed to remove a user from the project.');
        }
        $project->users()->detach($user);
        return redirect()->back()->with('success', 'User removed from project.');
    }

    public function addUsers(Project $project)
    {
        $users = User::all();
        return Inertia::render('Projects/AddUsers', ['project' => $project, 'users' => $users]);
    }

    public function storeUsers(Request $request, Project $project)
    {
        $attributes = $request->validate([
            'userIds' => 'required|array',
        ]);

        // Prepare the data for syncWithoutDetaching
        $syncData = [];
        foreach ($attributes['userIds'] as $userId) {
            $syncData[$userId] = [
                'role_id' => 2,
                'joined_at' => now(),
            ];
        }
        $project->users()->syncWithoutDetaching($syncData);
        return redirect()->route('projects.index')->with('success', 'Users added to project.');
    }

    public function updateUserRole(Request $request, Project $project, ProjectUser $projectUser)
    {
        $projectUser->update($request->validate([
            'role_id' => 'required|exists:roles,id',
        ]));
        return redirect()->route('projects.index')->with('success', 'User role updated.');
    }

    public function storeSelectedProject(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $request->session()->put('project_id', $request->project_id);

        return redirect()->back()->with('success', 'Project switched!');
    }
}
