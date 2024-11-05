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
        $tenants = $request->user()->tenants;

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

        //check if user is tenant admin
        if ($request->user()->isAdminInTenant($request->tenant_id)) {
            $tenant = Tenant::find($request->tenant_id);
            //check if tenant has projects if yes access the last accessed project
            if ($tenant->projects->count() > 0) {
                session('project_id') ?? $request->session()->put('project_id', $tenant->projects->last()->id);
            } else {
                $request->session()->put('project_id', null);
            }
        } else {
            if ($request->user()->projects->count() > 0) {
                session('project_id') ?? $request->session()->put('project_id', $request->user()->projects->last()->id);
            } else {
                Tenant::find($request->tenant_id)->projects()->create([
                    'name' => 'Default Project',
                    'description' => 'Default Project',
                    'code' => uniqid(),
                    'start_date' => now(),
                    'end_date' => now()->addDays(30),
                ]);
                $project = Project::where('tenant_id', $request->tenant_id)->where('name', 'Default Project')->first();
            }
        }

        return redirect()->route('dashboard');
    }
}
