<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{

    function getUsers(Request $req)
    {
        $logged_in_user_id = Auth::id();

        $allUsers = User::whereNotIn('id', [$logged_in_user_id])->get();

        return $this->sendResponse($allUsers, "All users");
    }
}
