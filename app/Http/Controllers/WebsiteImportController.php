<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WebsiteImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Website $website)
    {
        return Inertia::render('Websites/Imports/Index', [
            'website' => $website,
        ]);
    }


}
