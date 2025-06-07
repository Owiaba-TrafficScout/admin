<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $project = Project::find(session('project_id'));
        $users = $project->users()->with('pivot.role')->get();
        $allUsers = User::all();
        $roles = [];
        if (auth()->user()->isAdminInTenant()) {
            $roles = TenantRole::all();
        } else {
            $roles = Role::all();
        }

        $project = Project::find(session('project_id'));
        return Inertia::render('Users', [
            'users' => $users,
            'roles' => $roles,
            'project' => $project,
            'allUsers' => $allUsers,
        ]);
    }

    public function update(Request $request, User $user)
    {
        if ($user->isAdminInTenant()) {
            return redirect()->back()->with("error", "You can't update the system admin");
        } else if ($user->isAdminInProject() and !$request->user()->isAdminInTenant()) {
            return redirect()->back()->with("error", "You can't update the project admin");
        }

        $projectUser = ProjectUser::where('user_id', $user->id)->where('project_id', session('project_id'))->first();
        $projectUser->update($request->validate([
            'role_id' => ['required', 'integer'],
        ]));


        return redirect()->back()->with("success", "Role Updated");
    }

    // public function destroy(User $user)
    // {
    //     if ($user->isAdminInTenant()) {
    //         return redirect()->back()->with("error", "You can't remove a system admin");
    //     } else if (auth()->user()->isAdminInTenant()) {
    //         $tenant = Tenant::find(session('tenant_id'));
    //         $tenant->users()->detach($user->id);
    //         return redirect()->back()->with("success", "User removed from your organization");
    //     } else {
    //         //remove users from projects
    //         $projectIds = auth()->user()->adminProjects->pluck('id');
    //         //detach all projectIds that the user has that are found in $projectIds
    //         $user->projects()->detach($projectIds);

    //         return redirect()->back()->with("success", "User Removed from all your projects");
    //     }
    // }
    public function destroy(User $user)
    {
        if ($user->isAdminInProject()) {
            return redirect()->back()->with("error", "You can't remove admin");
        } else {
            //remove user from current project
            $user->projects()->detach(session('project_id'));

            return redirect()->back()->with("success", "User Removed from project!");
        }
    }
}
