<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\AuthUserRequest;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\ValidationException;

class AuthUserHandler extends Controller
{
    public function __invoke(AuthUserRequest $request)
    {

        /** @var User $user */
        $user = User::whereEmail($request->string('email'))
            ->first();

        if (!$user || !Hash::check($request->string('password'), $user->password)) {
            if ($user)
                $this->log(auth()->user(), 'User auth failed', $user);

            throw ValidationException::withMessages([
                'email' => 'Wrong email or password',
                'password' => 'Wrong email or password',
            ]);
        }

        if (!$user->hasPermissionTo(auth()->user()->permission)) {
            $this->log(auth()->user(), 'User is not authorized to use that application', $user);
            throw ValidationException::withMessages([
                'permissions' => 'That user doesn\'t have permissions to use that application. Please contact with administrator to gain access to that app.',
            ]);
        }

        $token = session()->getId();

        $user->session_token = Hash::make($token);
        $user->session_token_expires_at = Carbon::now()->addMinutes(config('session.lifetime'));
        $user->save();

        $this->log(auth()->user(), 'User has logged into the system', $user);

        return (new UserResource($user))
            ->additional([
                'token' => [
                    'token' => "{$user->hashed_id}.$token",
                    'expires_at' => $user->session_token_expires_at->toAtomString()
                ]
            ]);
    }
}
