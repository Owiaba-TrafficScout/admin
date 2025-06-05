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
        $isTenantAdmin = $user && $user->isAdminInTenant();
        $isProjectAdmin = $user && $user->isAdminInProject();
        $tenant = $user?->tenants()->find(session('tenant_id'));
        $projectId = session('project_id');
        $lastProjectId = $tenant?->projects->last()?->id;
        $selectedProject = $isTenantAdmin
            // For tenant admins:
            ? $tenant?->projects()->find($projectId ?? $lastProjectId)
            // For non-tenant admins:
            : (
                $user?->projects->find($projectId)
                ?: $user?->adminProjects()
                ->where('tenant_id', session('tenant_id'))
                ->orderByDesc('created_at')
                ->first()
            );

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
            'projects' => $isTenantAdmin
                ? Tenant::find(session('tenant_id'))->projects
                : $user?->projects()
                ->where('tenant_id', session('tenant_id'))
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->where('role_id', \App\Models\Role::where('name', 'admin')->first()->id);
                })->get(),
            'selected_project' => $selectedProject
        ];
    }
}
