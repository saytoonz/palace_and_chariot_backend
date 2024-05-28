<?php
namespace  App\Traits;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

trait CountryTrait {

    public function getCountry(Request $request)
    {
        $userIp = $request->ip();
            $client = new Client();
            $response = $client->get("https://ipinfo.io/{$userIp}?token=8e1df421b8ccd6");
            $data = json_decode($response->getBody());
            $country = $data->country ?? 'Unknown country';

        return $country;
    }
}
