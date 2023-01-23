<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UpdateUserHandler extends Controller
{
    public function __invoke(UpdateUserRequest $request, User $user)
    {
        $user->name = $request->string('name');
        $user->email = $request->string('email');

        if ($user->isDirty()) {
            $user->save();
            $this->log(auth()->user(), 'User has been updated', $user, $user->getChanges());
        }

        return new UserResource($user);
    }
}
