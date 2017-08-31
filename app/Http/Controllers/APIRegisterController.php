<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\User;
use Illuminate\Http\Request;

class APIRegisterController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'result' => $user->username
        ]);
    }
}
