<?php

namespace App\Actions\Fortify;

use App\Models\TeamInvitation;
use App\Models\User;
use App\Rules\InvitedUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new InvitedUser],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $invitations = TeamInvitation::where('email', $input['email'])->whereNull('used_at')->get();

        if (! $invitations) {
            abort('403');
        }

        return DB::transaction(function () use ($input, $invitations) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) use ($invitations) {
                foreach ($invitations as $invitation) {
                    $invitation->update(['used_at' => now()]);
                    $team = $invitation->team;
                    $user->teams()->attach($team->id, ['role' => $invitation->role]);
                    $user->switchTeam($team);
                }
            });
        });
    }
}
