<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    public function saveRequest(Request $request)
    {
        User::attachRequestToUser($request->post('user_id'), $request);

        return response()->json([
            'response' => 'ok'
        ]);
    }
}
