<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Users;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('users')->group(function () {
    Route::post('/', Users\StoreUserHandler::class)->name('users.store');
    Route::post('/auth', Users\AuthUserHandler::class)->name('users.auth');
    Route::post('/validate', Users\ValidateUserSessionHandler::class)->name('users.validate');
    Route::put('/{user}', Users\UpdateUserHandler::class)->name('users.update');
});
