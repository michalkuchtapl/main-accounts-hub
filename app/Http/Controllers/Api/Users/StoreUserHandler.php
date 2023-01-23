<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StoreUserHandler extends Controller
{
    public function __invoke(StoreUserRequest $request)
    {
        $user = new User();
        $user->name = $request->string('name');
        $user->email = $request->string('email');
        $user->password = Hash::make($request->string('password'));
        $user->hashed_id = Str::random(16);
        $user->save();

        $this->log(auth()->user(), 'User has been created', $user);

        $user->givePermissionTo(auth()->user()->permission);

        $this->log(auth()->user(), 'Permission for app granted', $user, ['app_name' => auth()->user()->name]);

        return new UserResource($user);
    }
}
