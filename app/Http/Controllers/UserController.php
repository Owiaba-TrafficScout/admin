<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        if (auth()->user()->isProjectAdmin()) {
            $userIdsWithSameProjects = auth()->user()->projects->pluck('users')->flatten()->pluck('id');
            $users = User::WhereIn('id', $userIdsWithSameProjects)
                ->where('id', '!=', auth()->user()->id)
                ->where('role_id', '>', auth()->user()->role_id)
                ->get();
        } else {
            $users = User::where('id', '!=', auth()->user()->id)->get();
        }
        return Inertia::render('Users', [
            'users' => $users,
            'roles' => Role::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        if ($user->role->name === 'system admin') {
            return redirect()->back()->with("error", "You can't update the system admin");
        } else if ($user->role->name = 'project admin' && auth()->user()->role->name !== 'system admin') {
            return redirect()->back()->with("error", "You can't update the project admin");
        } else if ($user->id == auth()->user()->id) {
            return redirect()->back()->with("error", "You can't update yourself");
        }
        $user->update($request->validate([
            'role_id' => ['required', 'integer'],
        ]));

        return redirect()->back()->with("success", "Role Updated");
    }

    public function destroy(User $user)
    {
        if ($user->role->name === 'system admin') {
            return redirect()->back()->with("error", "You can't remove a system admin");
        }

        if (auth()->user()->isSystemAdmin()) {
            $user->delete();
            return redirect()->back()->with("success", "User Deleted");
        } else {
            //remove users from projects
            $projectIds = auth()->user()->projects->pluck('id');
            //detach all projectIds that the user has that are found in $projectIds
            $user->projects()->detach($projectIds);

            return redirect()->back()->with("success", "User Removed from all yuor projects");
        }
    }
}
