<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;

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
}
