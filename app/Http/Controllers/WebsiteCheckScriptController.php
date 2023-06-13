<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class WebsiteCheckScriptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Website $website)
    {
        $scriptUrl = config('app.url').'/'.config('counted.script_name').'.js';

        try {
            // Fetch only the head section of the website's HTML content
            $response = Http::get('//' . $website->domain);
            $htmlContent = $response->body();
            $headContent = substr($htmlContent, 0, strpos($htmlContent, '</head>') + 7);

            // Check if the script URL is present and website ID matches
            $scriptTagExists = strpos($headContent, $scriptUrl) !== false;
            $websiteIdMatches = strpos($headContent, 'website="' . $website->id . '"') !== false;

            if ($scriptTagExists && $websiteIdMatches) {
                return redirect()->back()->withSuccess(['check' => 'Script URL and website ID match.']);
            }

            if(!$scriptTagExists && !$websiteIdMatches)
                return redirect()->back()->withErrors(['check' => 'The script does not seem to be present on your site.']);

            if(!$scriptTagExists)
                return redirect()->back()->withErrors(['check' => 'Script URL doesn\'t match.']);

            if(!$websiteIdMatches)
                return redirect()->back()->withErrors(['check' => 'Website ID doesn\'t match.']);

            //return response()->json(['result' => 'Script URL or website ID does not match.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
