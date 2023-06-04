<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Torann\GeoIP\Facades\GeoIP;

class EventController extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required',
            'website' => 'required|exists:websites,id',
        ]);

        $token = $request->input('token', Str::uuid());
        $visitor = Visitor::firstOrNew(['token' => $token]);

        if (! $visitor->exists) {
            $agent = new Agent();
            $ipAddress = $request->header('x-forwarded-for') ?: $request->ip();
            $geoip = GeoIP::getLocation($ipAddress);

            $visitor->fill([
                'token' => Str::uuid(),
                'website_id' => $validatedData['website'],
                'referer_domain' => parse_url($request->referrer, PHP_URL_HOST),
                'browser' => $agent->browser(),
                'os' => $agent->platform(),
                'device' => $agent->device(),
                'screen' => $request->input('screen'),
                'language' => $request->input('language'),
                'country' => $geoip->iso_code,
                'city' => $geoip->city,
                'lat' => $geoip->lat,
                'lon' => $geoip->lon,
            ])->save();
        } else {
            $visitor->touch();
        }

        if ($request->input('type') === 'view') {
            $visitor->views()->create([
                'website_id' => $request->input('website'),
                'url_path' => parse_url($request->input('url'), PHP_URL_PATH),
                'url_query' => parse_url($request->input('url'), PHP_URL_QUERY),
                'referer_path' => parse_url($request->input('referrer'), PHP_URL_PATH),
                'referer_query' => parse_url($request->input('referrer'), PHP_URL_QUERY),
                'referer_domain' => parse_url($request->input('referrer'), PHP_URL_HOST),
                'page_title' => $request->input('title'),
            ]);
        }

        if ($request->input('type') === 'event') {
            $visitor->events()->create([
                'website_id' => $request->input('website'),
                'name' => $request->input('eventData'),
            ]);
        }

        return response()->json([
            'token' => $visitor->token,
        ]);
    }
}
