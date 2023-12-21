<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function event(){
        TestEvent::dispatch(auth()->id());

        return response()->json([
            'message' => 'Event dispatched',
            'user_id' => auth()->id(),
        ], 200);
    }

    public function photo(){
        $this->validate(request(), [
            'photo' => 'required|image|max:2048',
        ]);
        $user = auth()->user();

        $user->update([
            'photo' => request()->file('photo')->store('photos', 'public'),
        ]);
        $user->save();

        return response()->json([
            'message' => 'Photo uploaded',
            'user' => $user,
        ], 200);
    }
}
