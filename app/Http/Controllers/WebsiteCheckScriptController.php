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
    public function __invoke(Request $request, Website $website)
    {
        $websiteUrl = $request->input('website_url');
        $scriptUrl = $request->input('script_url');
        $websiteId = $request->input('website_id');

        try {
            // Fetch only the head section of the website's HTML content
            $response = Http::get($websiteUrl);
            $htmlContent = $response->body();
            $headContent = substr($htmlContent, 0, strpos($htmlContent, '</head>') + 7);

            // Check if the script URL is present and website ID matches
            $scriptTagExists = strpos($headContent, $scriptUrl) !== false;
            $websiteIdMatches = strpos($headContent, 'website="' . $websiteId . '"') !== false;

            if ($scriptTagExists && $websiteIdMatches) {
                return response()->json(['result' => 'Script URL and website ID match.']);
            }
            
            return response()->json(['result' => 'Script URL or website ID does not match.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
