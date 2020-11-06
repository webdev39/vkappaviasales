<?php

namespace App\Services;

use App\Models\Request;
use App\Models\User;

class UserCheapFlightsApiMessagesService
{
    private $api;

    public function __construct()
    {
        $this->api = new VkApi();
    }

    public function sendApiMessagesToAllUsers()
    {
        $users = User::all();
        foreach ($users as $user) {
            $this->sendUserAllMessages($user);
        }
    }

    public function sendUserAllMessages($user)
    {
        foreach (Request::getAllByUserId($user->id) as $request) {
            foreach ($request->getFlightsFromApi() as $flight) {
                if (!$user->hasRequestReceived($flight['id']) && $request->inRequestAllowableUpdatedDatesRadius($flight)) {
                    sleep(15);
                    $this->api->messagesSend(
                        ['user_id' => $user->id],
                        $request->makeRequestMessage($flight),
                        getenv('MIX_MAIN_VK_PUBLIC_ID')
                    );
                    $user->receivedRequest($flight['id']);
                }
            }
        }
    }
}
