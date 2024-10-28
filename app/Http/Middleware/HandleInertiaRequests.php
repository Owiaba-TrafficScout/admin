<?php

namespace App\Http\Middleware;

use App\Models\Project;
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
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'is_tenant_admin' => $request->user() ? $request->user()->isAdminInTenant() : false,
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error')
            ],
            'projects' => $request->user() ? ($request->user()->isAdminInTenant() ? $request->user()->tenants->find(session('tenant_id'))->projects : $request->user()->projects) : [],
            'selected_project' => $request->user() ? ($request->user()->isAdminInTenant() ? $request->user()->tenants->find(session('tenant_id'))->projects->find($request->session()->get('project_id')) : $request->user()->projects->find($request->session()->get('project_id'))) : null,
        ];
    }
}
