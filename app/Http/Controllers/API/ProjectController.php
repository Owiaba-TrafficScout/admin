<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\JoinProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class ProjectController extends Controller
{
    public function index(Request $request)
    {

        $user = $request->user();

        $projects = $user->projects()
            ->with(['trips', 'carTypes'])
            ->get();

        $activeProject = $user->activeProject();

        return ProjectResource::collection($projects)->additional([
            'active_project' => $activeProject ? new ProjectResource($activeProject) : null,
        ]);
    }

    public function joinProject(JoinProjectRequest $request)
    {
        $attributes = $request->validated();

        $project = Project::where('code', $attributes['code'])->first();

        $roleId = Role::where('name', 'enumerator')->value('id');
        $project->users()->syncWithoutDetaching([
            Auth::id() => [
                'role_id'   => $roleId,
                'joined_at' => now(),
            ],
        ]);

        return new ProjectResource($project);
    }
}
