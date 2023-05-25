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
            'website_id' => ['required', 'exists:websites,id'],
        ]);

        // Retrieve browser, OS, and device information
        $agent = new Agent();
        $browser = $agent->browser();
        $os = $agent->platform();
        $device = $agent->device();

        // Retrieve screen size
        $screenWidth = $request->header('x-screen-width');
        $screenHeight = $request->header('x-screen-height');

        // Retrieve language
        $language = $request->header('accept-language');

        // Retrieve IP address
        $ipAddress = $request->header('x-forwarded-for') ?: $request->ip();

        // Retrieve country and city
        $geoip = GeoIP::getLocation($ipAddress);
        $country = $geoip->country;
        $city = $geoip->city;

        // You can now use the retrieved information as needed
        // For example, store it in a database or send it to an API

        return response()->json([
            'ip' => $ipAddress,
            'browser' => $browser,
            'os' => $os,
            'device' => $device,
            'screen_width' => $screenWidth,
            'screen_height' => $screenHeight,
            'language' => $language,
            'country' => $country,
            'city' => $city,
        ]);

    }
    
}
