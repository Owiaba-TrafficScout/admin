<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\TenantRole;
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
        } else if ($user->pivot->role->name = 'admin' && !auth()->user()->isAdminInTenant()) {
            return redirect()->back()->with("error", "You can't update the admin");
        } else if ($user->id == auth()->user()->id) {
            return redirect()->back()->with("error", "You can't update yourself");
        }
        $user->update($request->validate([
            'role_id' => ['required', 'integer'],
        ]));

        return redirect()->back()->with("success", "Role Updated");
    }

    public function destroy(User $user)
    {
        if ($user->role->name === 'system admin') {
            return redirect()->back()->with("error", "You can't remove a system admin");
        }

        if (auth()->user()->isSystemAdmin()) {
            $user->delete();
            return redirect()->back()->with("success", "User Deleted");
        } else {
            //remove users from projects
            $projectIds = auth()->user()->projects->pluck('id');
            //detach all projectIds that the user has that are found in $projectIds
            $user->projects()->detach($projectIds);

            return redirect()->back()->with("success", "User Removed from all yuor projects");
        }
    }
}
