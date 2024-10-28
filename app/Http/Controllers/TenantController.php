<?php

namespace App\Http\Controllers;

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
                $request->session()->put('project_id', $tenant->projects->last()->id);
            } else {
                $request->session()->put('project_id', null);
            }
            
        } else {
            if ($request->user()->projects->count() > 0) {
                $request->session()->put('project_id', $request->user()->projects->last()->id);
            } else {
                $request->session()->put('project_id', null);
            }
        }

        return redirect()->route('dashboard');
    }
}
