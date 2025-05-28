<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function getProjects(Request $request)
    {
        $email = $request->input('email');
        $response = ['project' => [], 'settings' => []];

        try {
            $user = \App\Models\User::where('email', $email)->firstOrFail();
            $currentState = \App\Models\State::where('user_id', $user->id)->first();
            $currentProjectId = $currentState ? $currentState->project_id : 0;

            $userProjects = $user->projects()->orderByDesc('id')->get();

            foreach ($userProjects as $userProject) {
                $project = [
                    'activeProject' => ($userProject->id == $currentProjectId) ? 1 : 0,
                    'idProject' => $userProject->id,
                    'projectName' => $userProject->name,
                    'projectCode' => $userProject->code,
                    'tenantId' => $userProject->tenant_id,
                    'cartype' => []
                ];

                $now = now();
                $startDate = new \DateTime($userProject->start_date);
                $endDate = new \DateTime($userProject->end_date);

                if ($now < $endDate) {
                    $project['projectStatus'] = ($now < $startDate) ? 0 : 1;

                    $carTypes = $userProject->carTypes;
                    foreach ($carTypes as $carType) {
                        $project['cartype'][] = [
                            'idCarType' => $carType->id,
                            'carTypeName' => $carType->name
                        ];
                    }
                } else {
                    $project['projectStatus'] = 2;
                }

                $response['project'][] = $project;
            }

            $response['success'] = 1;
            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json(['success' => 0], 500);
        }
    }
}
