<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamInvitation;
use Illuminate\Support\Facades\Auth;
use Inertia;

class InvitedUserController extends Controller
{

    public function create($invitationToken)
    {
        $invitation = TeamInvitation::where('token', $invitationToken)->first();

        if (!$invitation || $invitation->used) {
            abort(404);
        }

        return Inertia::render('Auth/Register', ['invitationToken' => $invitationToken]);
    }

    public function store(Request $request)
    {
        $invitation = TeamInvitation::where('token', $request->input('invitation_token'))->first();

        if (!$invitation || $invitation->used) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $team = $invitation->team;
        $team->users()->attach($user->id);

        $invitation->update(['used' => true]);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

}
