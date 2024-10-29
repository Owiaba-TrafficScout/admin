<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $isTenantAdmin = $user ? $user->isAdminInTenant() : false;
        $isProjectAdmin = $user ? $user->isAdminInProject() : false;

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'is_tenant_admin' => $isTenantAdmin,
                'is_project_admin' => $isProjectAdmin,
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error')
            ],
            'projects' => $isTenantAdmin ? Tenant::find(session('tenant_id'))->projects : $user?->projects,
            'selected_project' => $isTenantAdmin ? $user->tenants->find(session('tenant_id'))->projects->find($request->session()->get('project_id')) : $user?->projects->find($request->session()->get('project_id'))
        ];
    }
}
