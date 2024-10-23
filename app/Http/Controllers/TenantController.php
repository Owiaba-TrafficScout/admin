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

        return redirect()->route('dashboard');
    }
}
