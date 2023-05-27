<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use \Torann\GeoIP\Facades\GeoIP;

class EventController extends Controller
{

    public function __invoke(Request $request) {

        $request->validate([
            'website' => ['required', 'exists:websites,id'],
        ]);

        // Retrieve browser, OS, and device information
        $agent = new Agent();
        $browser = $agent->browser();
        $os = $agent->platform();
        $device = $agent->device();

        // Retrieve language
        $language = $request->header('accept-language');

        // Retrieve IP address
        $ipAddress = $request->header('x-forwarded-for') ?: $request->ip();

        // Retrieve country and city
        $geoip = GeoIP::getLocation($ipAddress);
        $country = $geoip->country;
        $city = $geoip->city;

        dd([
            'type' => $request->type,
            'url' => $request->url,
            'title' => $request->title,
            'screen' => $request->screen,
            'language' => $request->language,
            'history' => $request->history,
            'website' => $request->website,
            'load_time' => $request->load_time,
            'ip' => $ipAddress,
            'browser' => $browser,
            'os' => $os,
            'device' => $device,
            'language' => $language,
            'country' => $country,
            'city' => $city,
        ]);

    }
    
}
