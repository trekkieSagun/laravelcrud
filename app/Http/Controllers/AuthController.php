<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{

    public function register(Request $req)
    {
        $data = $req->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $success['name'] =  $user->name;



        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $req)
    {
            if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
                $user = Auth::user();
                $success['token'] =  $user->createtoken('MyApp')->accessToken;
                $success['name'] =  $user->name;

                return $this->sendResponse($success, 'User login successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        }
    
}
