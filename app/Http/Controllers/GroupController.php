<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\UserChat;
use Illuminate\Http\Request;
use App\Models\User;

class GroupController extends Controller
{
    public function save(Request $request)
    {
        $userId = $request->post('user_id');
        $appId = $request->post('app_id');
        $value = $request->post('checkbox');
        $user = User::find($userId) ?? new User();
        $user->id = $userId;
        $user->save();

        $group = Group::where(['number' => $value])->first() ?? new Group();
        $group->number = $value;
        $group->save();
        $user->groups()->save($group);

        return response()->json([
            'response' => '/?vk_user_id=' . $userId . '&vk_app_id=' . $appId
        ]);
    }

    public function checkGroupEnable(Request $request)
    {
        $userId = $request->post('userId');
        $groupId = $request->post('groupId');
        $chat = UserChat::where(
            [
                ['user_id', '=', $userId],
                ['chat_id', '=', $groupId]
            ]
        )->first();

        return response()->json([
            'response' => $chat ? true : false
        ]);
    }
}
