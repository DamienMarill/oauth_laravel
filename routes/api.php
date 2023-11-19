<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/auth/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::where('google_id', $googleUser->id)->first();

    if ($user){
        $user->update([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
        ]);
    }else{
        $user = new User();
        $user->name = $googleUser->name;
        $user->email = $googleUser->email;
        $user->google_id = $googleUser->id;
        $user->save();
    }
    return [
        'user' => $user,
        'token' => $user->createToken('spa')->plainTextToken,
    ];
});
