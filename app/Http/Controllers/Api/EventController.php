<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedData = $request->validate([
            'type' => ['required'],
            'website' => ['required', 'exists:websites,id'],
        ]);

        $token = $request->token ?? Str::uuid();
        $visitor = Visitor::firstOrNew(['token' => $request->token]);

        if (!$visitor->exists) {
            $agent = new Agent();
            $ipAddress = $request->header('x-forwarded-for') ?: $request->ip();
            $geoip = GeoIP::getLocation($ipAddress);

            $visitor->fill([
                'token' => Str::uuid(),
                'website_id' => $validatedData['website'],
                'browser' => $agent->browser(),
                'os' => $agent->platform(),
                'device' => $agent->device(),
                'screen' => $request->screen,
                'language' => $request->language,
                'country' => $geoip->iso_code,
                'city' => $geoip->city,
                'lat' => $geoip->lat,
                'lon' => $geoip->lon,
            ])->save();
        } else {
            $visitor->touch();
        }

        if ($request->type === 'view') {
            $visitor->views()->create([
                'website_id' => $request->website,
                'type' => "view",
                'url_path' => parse_url($request->url, PHP_URL_PATH),
                'url_query' => parse_url($request->url, PHP_URL_QUERY),
                'referer_path' => parse_url($request->referrer, PHP_URL_PATH),
                'referer_query' => parse_url($request->referrer, PHP_URL_QUERY),
                'referer_domain' => parse_url($request->referrer,  PHP_URL_HOST),
                'page_title' => $request->title,
            ]);
        }

        return response()->json([
            'token' => $visitor->token,
        ]);
    }
}
