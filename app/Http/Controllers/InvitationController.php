<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Invitation;
use App\Models\Project;
use App\Models\User;
use App\Notifications\InvitationNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class InvitationController extends Controller
{
    public function sendInvite(Request $request, Project $project)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $token = Str::random(40);
        $invitation = Invitation::create([
            'email' => $request->email,
            'role_id' => 2,
            'project_id' => $project->id,
            'accepted' => false,
            'token' => $token,
            'expires_at' => now()->addDays(7),
        ]);

        $signedUrl = URL::temporarySignedRoute(
            'invite.accept',
            now()->addDays(7),
            ['token' => $token]
        );

        // Send the invitation via a notification
        $invitation->notify(new InvitationNotification($signedUrl));

        return back()->with('success', 'Invitation sent successfully');
    }

    public function accept(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        $expDate = Carbon::parse($invitation->expires_at);



        if (!$request->hasValidSignature() || $expDate->isPast()) {
            abort(403, 'This invitation link has expired.');
        }

        if (User::where('email', $invitation->email)->exists()) {
            $user = User::where('email', $invitation->email)->first();
            $user->projects()->attach($invitation->project_id, ['role_id' => $invitation->role_id, 'joined_at' => now()]);
            $invitation->delete();
            return redirect()->route('dashboard');
        } else {
            return Inertia::render('Invitation/Register', ['invitation' => $invitation]);
        }
    }

    public function register(Request $request)
    {
        $attributes = $request->validate([
            'email' => 'required|email|exists:invitations,email',
            'password' => 'required|confirmed|min:8',
            'name' => 'required|string|max:255',
        ]);

        $invitation = Invitation::where('email', $request->email)->firstOrFail();

        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
            'email_verified_at' => now(),
        ]);

        $user->projects()->attach($invitation->project_id, ['role_id' => $invitation->role_id, 'joined_at' => now()]);

        $invitation->delete(); // delete the invitation after successful registration

        //redirect user to login page
        return redirect()->route('login');
    }
}
