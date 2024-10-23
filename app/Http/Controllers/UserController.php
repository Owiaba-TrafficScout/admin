<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $tenant = Tenant::find(session('tenant_id'));
        $users = [];
        $roles = [];
        if (auth()->user()->isAdminInTenant()) {
            $users = $tenant->users()->with('pivot.role')->get();
            $roles = TenantRole::all();
        } else {
            $users = auth()->user()->projects()->with('users.pivot.role')->get()->pluck('users')->unique('id')->flatten();
        }
        return Inertia::render('Users', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        if ($user->isAdminInTenant()) {
            return redirect()->back()->with("error", "You can't update the system admin");
        }
        $tenantUser = TenantUser::where('user_id', $user->id)->where('tenant_id', session('tenant_id'))->first();
        $tenantUser->update($request->validate([
            'tenant_role_id' => ['required', 'integer'],
        ]));

        return redirect()->back()->with("success", "Role Updated");
    }

    public function destroy(User $user)
    {
        if ($user->isAdminInTenant()) {
            return redirect()->back()->with("error", "You can't remove a system admin");
        } else if (auth()->user()->isAdminInTenant()) {
            $tenant = Tenant::find(session('tenant_id'));
            $tenant->users()->detach($user->id);
            return redirect()->back()->with("success", "User removed from your organization");
        } else {
            //remove users from projects
            $projectIds = auth()->user()->adminProjects->pluck('id');
            //detach all projectIds that the user has that are found in $projectIds
            $user->projects()->detach($projectIds);

            return redirect()->back()->with("success", "User Removed from all your projects");
        }
    }
}
