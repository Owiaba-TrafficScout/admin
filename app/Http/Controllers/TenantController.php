<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TenantController extends Controller
{
    public function selectTenant(Request $request)
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            // Option A: show all tenants
            $tenants = Tenant::all();
            return Inertia::render('SelectTenant', [
                'tenants' => $tenants,
            ]);
        }

        // Option B: only tenants where the user is Admin on at least one project or is tenant admin
        $adminRoleId = \App\Models\Role::where('name', 'Admin')->value('id');
        $tenants = Tenant::whereHas('projects.users', function ($q) use ($user, $adminRoleId) {
            $q->where('user_id', $user->id)
                ->where('role_id', $adminRoleId);
        })->orWhereHas('users', function ($q) use ($user, $adminRoleId) {
            $q->where('user_id', $user->id)
                ->where('tenant_role_id', $adminRoleId);
        })->get();

        return Inertia::render('SelectTenant', [
            'tenants' => $tenants,
        ]);
    }

    public function storeSelectedTenant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $request->session()->put('tenant_id', $request->tenant_id);
        $request->user()->state()->updateOrCreate(
            [],
            ['tenant_id' => $request->tenant_id]
        );

        //check if user is tenant admin
        if ($request->user()->isAdminInTenant($request->tenant_id) || $request->user()->isSuperAdmin()) {


            $tenant = Tenant::find($request->tenant_id);
            //check if tenant has projects if yes access the last accessed project
            if ($tenant->projects->count() > 0) {
                session('project_id') ?? $request->session()->put('project_id', $tenant->projects->last()->id);
            } else {
                Tenant::find($request->tenant_id)->projects()->create([
                    'name' => 'Default Project',
                    'description' => 'Default Project',
                    'code' => uniqid(),
                    'start_date' => now(),
                    'end_date' => now()->addDays(30),
                ]);
                $project = Project::where('tenant_id', $request->tenant_id)->where('name', 'Default Project')->first();

                $request->session()->put('project_id', $project->id);
            }
        } elseif ($project = $request->user()->adminProjects()
            ->where('tenant_id', $request->tenant_id)
            ->orderByDesc('created_at')
            ->first()
        ) {
            $request->session()->put('project_id', $project->id);
        } else {
            // unauthorized
            return back();
        }

        return redirect()->route('dashboard');
    }
}
