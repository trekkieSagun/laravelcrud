<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends BaseController
{

    public function register(Request $req)
    {
        $data = $req->all();
        $randomString = Str::random(10);

        $newImageName = time() . '-' . $randomString . '.' . $req->profileImg->extension();

        $req->profileImg->move(public_path('images'), $newImageName);


        $data['profileImg'] = $newImageName;

        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);


        if ($user) {
            $success['name'] =  $user->name;
            $success['email'] =  $user->email;
            $success['profileImg'] = $user->profileImg;
            return $this->sendResponse($success, 'User register successfully.');
        } else {
            return $this->sendError('error', ['error' => 'Cannot register user']);
        }
    }

    public function login(Request $req)
    {
        if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createtoken('MyApp')->accessToken;
            $success['name'] =  $user->name;
            $success['email'] =  $user->email;
            $success['profileImg'] = $user->profileImg;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
}
