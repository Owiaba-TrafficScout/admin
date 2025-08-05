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
        $state = $user?->state;
        $isSuperAdmin = $user?->isSuperAdmin();
        $isTenantAdmin = $user && $user->isAdminInTenant();
        $isProjectAdmin = $user && $user->isAdminInProject();
        $tenant = Tenant::find($state?->tenant_id);
        $projectId = $state?->project_id;
        $lastProjectId = $tenant?->projects->last()?->id;
        $selectedProject = null;
        $selectedTenant = $isSuperAdmin ? $tenant : null;

        if (!$isSuperAdmin) {
            $selectedProject = $isTenantAdmin
                // For tenant admins:
                ? $tenant?->projects()->find($projectId ?? $lastProjectId)
                // For non-tenant admins:
                : (
                    $user?->projects->find($projectId)
                    ?: $user?->adminProjects()
                    ->where('tenant_id', $state?->tenant_id)
                    ->orderByDesc('created_at')
                    ->first()
                );
        } else {
            // For super admins, we can just use the project ID from the session
            $selectedProject = Project::find($projectId);
        }

        // Fix: Update projects list to respect tenant selection for super admins
        $projects = null;
        if ($isSuperAdmin) {
            // For super admins, show projects for the selected tenant
            $projects = $tenant?->projects;
        } elseif ($isTenantAdmin) {
            // For tenant admins, show all tenant projects
            $projects = Tenant::find($state?->tenant_id)?->projects;
        } else {
            // For regular users, show only their admin projects
            $projects = $user?->projects()
                ->where('tenant_id', $state?->tenant_id)
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->where('role_id', \App\Models\Role::where('name', 'admin')->first()->id);
                })->get();
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'is_tenant_admin' => $isTenantAdmin,
                'is_project_admin' => $isProjectAdmin,
                'is_super_admin' => $isSuperAdmin,
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error')
            ],
            'projects' => $projects,
            'tenants' => $isSuperAdmin ? Tenant::all() : null,
            'selected_project' => $selectedProject,
            'selected_tenant' => $selectedTenant
        ];
    }
}
