<?php

namespace Tests\Feature;

use App\Services\VkApi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Request;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private $api;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->api = new VkApi();
    }

    public function testRequestMessageReceive()
    {
        $this->post('/save-user-api-request', [
            'user_id' => 448030989,
            'id' => 99991,
            'content' => '{"src.CountryCode":"RU","Discount":{"$gte":30},"SrcDayOfWeek":{"$in":[0,1,2,3,4,5,6]}, "Price":{"$gte":100,"$lte":500000},"Temp.Max":{"$gte":-50,"$lte":50},"Temp.Min":{"$gte":-50,"$lte":50},"Dst.continentCode":"E"}',
            'interval' => 60,
            'updated' => 99999,
            'limit' => 0,
            'output' => '',
        ]);

        $this->assertDatabaseHas('requests', [
            'id' => 99991,
        ]);

        $request = Request::find(99991);
        $user = User::find(448030989);

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

        $this->assertIsObject($request);
    }
}

