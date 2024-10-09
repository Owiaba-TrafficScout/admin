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
        return Inertia::render('Users', [
            'users' => User::all(),
            'roles' => Role::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        if ($user->role->name === 'system admin') {
            return redirect()->back()->with("error", "You can't update the system admin");
        }
        $user->update($request->validate([
            'role_id' => ['required', 'integer'],
        ]));

        return redirect()->back()->with("success", "Role Updated");
    }

    public function destroy(User $user)
    {
        if ($user->role->name === 'system admin') {
            return redirect()->back()->with("error", "You can't delete the system admin");
        }
        $user->delete();

        return redirect()->back()->with("success", "User deleted.");
    }
}
