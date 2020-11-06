<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find($request->vk_user_id) ?? new User();

        $hello_text = Message::where(['name' => 'hello_text'])->first() ?? new Message();
        $api_text = Message::where(['name' => 'api_text'])->first() ?? new Message();
        $partnerText = Message::where(['name' => 'partner_marker'])->first() ?? new Message();

        return view('welcome', [
            'helloText' => $hello_text->content,
            'apiText' => $api_text->content,
            'partnerText' => $partnerText->content,
            'groups' => $user->groups()->pluck('number')->toArray(),
        ]);
    }

    public function allowMessages()
    {
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function setupConfirmation(Request $request)
    {
        $confirmationCode = $request->post('confirmation_code');
        Cache::forever('confirmation_code', $confirmationCode);

        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function getCheapestTicket(Request $request)
    {
        $srcCityCode = $request->get('srcCityCode');
        $dstCityCode = $request->get('dstCityCode');

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://api.travelpayouts.com/v1/prices/cheap?'
            . 'origin=' . $srcCityCode
            . '&'
            . 'destination=' . $dstCityCode
            . '&'
            . '&'
            . 'token=' . getenv('MIX_AVIASALES_API_TOKEN')
        );

        return response()->json([
            'data' => $res->getBody()->getContents()
        ]);
    }
}
