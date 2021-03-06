<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ItineraryController extends Controller
{

    public function getCoordinate(Request $request){

        if(!$request->get('locations')) {
            return false;
        }
        $locations = explode(',', $request->get('locations'));

        $itins = [];

        foreach($locations as $key => $location) {
            $itins[$key]['location'] = $location;
            $client = new Client();
            $response = $client->request('GET', config('api.mapquest.uri'),
                ['query' => ['key' => config('api.mapquest.key'), "location" => str_replace('-', ' ',$location)]]);

            $res = $response->getBody()->getContents();
            $res = json_decode($res, true);
            if (isset($res['results'][0]['locations'][0]['latLng'])) {
                $itins[$key]['lat'] = $res['results'][0]['locations'][0]['latLng']['lat'];
                $itins[$key]['lng'] = $res['results'][0]['locations'][0]['latLng']['lng'];
            }
        }
        return $itins;
    }

}
