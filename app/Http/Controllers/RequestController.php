<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;

class RequestController extends Controller
{
    public function all(Request $request)
    {
        $userId = $request->post('user_id');
        $requests = RequestModel::where(['user_id' => $userId])->orderBy('id', 'DESC')->get();

        return response()->json([
            'requests' => $requests
        ]);
    }

    public function delete(Request $request)
    {
        $requestId = $request->post('request_id');
        RequestModel::where(['id' => $requestId])->delete();

        return response()->json([
            'response' => 'ok'
        ]);
    }
}
