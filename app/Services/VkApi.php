<?php

namespace App\Services;

use App\Models\User;

class VkApi
{
    private $params = [];
    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function messagesSend($data, $message, $groupId = null, $hasAttachment = true)
    {
        $user = User::query()->find($data['user_id']);
        $chat = $user->getChatOrCreateNew($groupId);
        $this->prepareMessageData($data, $groupId, $message, $hasAttachment);

        return $this->call(
            'messages.send',
            $chat->token
        );
    }

    public function prepareMessageData($data, $groupId, $message, $hasAttachment)
    {
        $arr = [
            'user_id' => $data['from_id'] ?? $data['user_id'],
            'group_id' => $groupId,
            'random_id' => rand(),
            'domain' => 'App',
            'message' => $message,
            'title' => 'Дешевые авиабилеты',
            'dont_parse_links' => 0
        ];

        if ($hasAttachment) {
            $arr['attachment'] = 'https://www.aviasales.ru/';
        }

        $this->params = array_merge(
            $this->params,
            $arr
        );
    }

    private function call($method, $accessToken = '')
    {
        $this->params['access_token'] = $accessToken;
        $this->params['v'] = getenv('VK_API_VERSION');

        $response = $this->client->post(
            getenv('VK_API_ENDPOINT') . $method,
            [
                'form_params' => $this->params
            ]
        );
        $result = json_decode($response->getBody()->getContents(), true);

        return $result['response'];
    }
}
