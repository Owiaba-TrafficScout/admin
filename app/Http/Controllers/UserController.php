<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\TenantUser;
use App\Models\User;
use Dom\Text;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    final const USER_TENANT_ROLE = 2; //tnenat user role id for user
    final const USER_SUCCESSFULLY_ADDED_TO_TENANT_MSG = 'Users added to your organization.';
    public function index(Request $request)
    {
        $project = Project::find($request->user()->state?->project_id);
        $users = $project->users()->with('pivot.role')->get();
        $allUsers = Tenant::find($request->user()->state?->tenant_id)->users()->with('pivot.role')->get();

        $roles = Role::all();

        return Inertia::render('Users', [
            'users' => $users,
            'roles' => $roles,
            'allUsers' => $allUsers,
        ]);
    }


    public function getTenantUsers(Request $request)
    {
        $users = Tenant::find($request->user()->state?->tenant_id)->users()->with('pivot.role')->get();
        $allUsers = User::all();
        $roles = TenantRole::all();

        return Inertia::render('Users', [
            'users' => $users,
            'roles' => $roles,
            'allUsers' => $allUsers,
        ]);
    }

    public function storeTenantUsers(Request $request)
    {
        $attributes = $request->validate([
            'userIds' => 'required|array',
            'userIds.*' => 'required|exists:users,id',
        ]);

        $tenant = Tenant::find($request->user()->state?->tenant_id);

        $syncData = [];
        foreach ($attributes['userIds'] as $userId) {
            $syncData[$userId] = [
                'tenant_role_id' => self::USER_TENANT_ROLE, // Assigning the tenant user role
            ];
        }

        $tenant->users()->syncWithoutDetaching($syncData);

        return redirect()->back()->with('success', self::USER_SUCCESSFULLY_ADDED_TO_TENANT_MSG);
    }



    public function updateTenantUser(Request $request, User $user)
    {
        $request->validate([
            'tenant_role_id' => ['required', 'integer', 'exists:tenant_roles,id'],
        ]);

        if ($user->isAdminInTenant()) {
            return redirect()->back()->with("error", "You can't update the system admin");
        }

        $tenantUser = TenantUser::where('user_id', $user->id)->where('tenant_id', $request->user()->state?->tenant_id)->first();
        $tenantUser->update([
            'tenant_role_id' => $request->tenant_role_id,
        ]);


        return redirect()->back()->with("success", "Role Updated");
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->isAdminInProject()) {
            return redirect()->back()->with("error", "You can't remove admin");
        } else {
            //remove user from current project
            $user->projects()->detach($request->user()->state?->project_id);

            return redirect()->back()->with("success", "User Removed from project!");
        }
    }
}
