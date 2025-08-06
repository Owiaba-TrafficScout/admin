<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\CarType;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tenant_id = $user->state?->tenant_id;
        $tenant = Tenant::find($tenant_id);
        $roles = Role::all();
        $projects = [];
        if ($user->isAdminInTenant()) {
            $projects = $tenant->projects->load(['trips', 'users.pivot.role']);
        } else {
            $projects = $user->adminProjects->load(['trips', 'users.pivot.role']);
        }

        return Inertia::render('Projects', ['projects' => $projects, 'roles' => $roles]);
    }

    public function create(Request $request)
    {
        if (!$request->user()->isAdminInTenant()) {
            return redirect()->back()->with('error', 'You are not allowed to create a project.');
        }
        $carTypes = CarType::orderByDesc('created_at')->get();
        return Inertia::render('Projects/Create', ['carTypes' => $carTypes]);
    }


    public function store(ProjectRequest $request)
    {
        if (!$request->user()->isAdminInTenant()) {
            return redirect()->back()->with('error', 'You are not allowed to create a project.');
        }



        $attributes = $request->validated();
        //generate project code which is unique in the projects table code column
        $attributes['code'] = uniqid();
        $attributes['tenant_id'] = $request->user()->state?->tenant_id;
        $carTypeIds = $attributes['carTypeIds'];
        unset($attributes['carTypeIds']);
        $project = Project::create($attributes);
        $project->carTypes()->attach($carTypeIds);

        return redirect()->route('users.index')->with('success', 'Project created.');
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $attributes =  $request->validated();

        $project->update($attributes);


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
        $authProjectUser = $project->users()->where('user_id', $request->user()->id)->first();
        if ($request->user()->id == $user->id) {
            return redirect()->back()->with('error', 'You can\'t remove yourself from the project.');
        } else if ($authProjectUser?->isProjectAdmin()) {
            if ($projectUser->isProjectAdmin()) {
                return redirect()->back()->with('error', 'You can\'t remove an admin from the project.');
            }
        } else if (!$request->user()->isAdminInTenant()) {
            return redirect()->back()->with('error', 'You are not allowed to remove a user from the project.');
        }
        $project->users()->detach($user);
        return redirect()->back()->with('success', 'User removed from project.');
    }

    public function storeUsers(Request $request)
    {
        $attributes = $request->validate([
            'userIds' => 'required|array',
        ]);

        $project = Project::find($request->user()->state?->project_id);

        // Prepare the data for syncWithoutDetaching
        $syncData = [];
        foreach ($attributes['userIds'] as $userId) {
            $syncData[$userId] = [
                'role_id' => 2,
                'joined_at' => now(),
            ];
        }
        $project->users()->syncWithoutDetaching($syncData);
        return redirect()->back()->with('success', 'Users added to project.');
    }

    public function updateUserRole(Request $request,  User $user)
    {
        $projectUser = ProjectUser::where('user_id', $user->id)
            ->where('project_id', $request->user()->state?->project_id)
            ->first();

        //Make sure only Tenant Admins can change roles of Project Admins
        if ($projectUser->isProjectAdmin() && !$request->user()->isAdminInTenant()) {
            return redirect()->back()->with('error', 'You cannot change the role of a project admin.');
        }

        $projectUser->update($request->validate([
            'role_id' => 'required|exists:roles,id',
        ]));
        return redirect()->back()->with('success', 'User role updated.');
    }

    public function storeSelectedProject(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        // Check if the user is an admin in the selected project
        if (!($request->user()->isAdminInTenant() || $request->user()->isAdminInProject($request->project_id))) {
            //unauthorized
            return redirect()->back()->with('error', 'You are not allowed to switch to this project.');
        }

        // Store project_id in database state instead of session
        $request->user()->state()->updateOrCreate(
            [],
            ['project_id' => $request->project_id]
        );

        return redirect()->back()->with('success', 'Project switched!');
    }
}
