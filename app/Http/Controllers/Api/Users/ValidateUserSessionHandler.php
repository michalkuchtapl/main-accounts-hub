<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\AuthUserRequest;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\ValidateUserSessionRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ValidateUserSessionHandler extends Controller
{
    public function __invoke(ValidateUserSessionRequest $request)
    {
        $token = explode('.', $request->string('token'));

        if (empty(data_get($token, '0')) || empty(data_get($token, '1')))
            return response()->json('Unauthenticated', 401);

        /** @var User $user */
        $user = User::whereHashedId(data_get($token, '0'))
            ->where('session_token_expires_at', '>=', Carbon::now())
            ->first();

        if (!$user || !Hash::check(data_get($token, '1'), $user->session_token))
            return response()->json('Unauthenticated', 401);

        if (!$user->hasPermissionTo(auth()->user()->permission)) {
            $this->log(auth()->user(), 'User is not authorized to use that application', $user);
            throw ValidationException::withMessages([
                'permissions' => 'That user doesn\'t have permissions to use that application. Please contact with administrator to gain access to that app.',
            ]);
        }

        $user->session_token_expires_at = Carbon::now()->addMinutes(config('session.lifetime'));
        $user->save();

        return (new UserResource($user))
            ->additional([
                'token' => [
                    'expires_at' => $user->session_token_expires_at->toAtomString()
                ]
            ]);
    }
}
