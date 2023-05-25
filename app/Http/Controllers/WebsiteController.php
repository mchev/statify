<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Rules\Domain;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Websites/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:websites', 'max:255', 'min:3'],
            'domain' => ['required', 'unique:websites', new Domain],
        ]);

        $website = $request->user()->currentTeam->websites()->create($validated);

        return redirect()->route('websites.edit', $website);
    }

    /**
     * Display the specified resource.
     */
    public function show(Website $website)
    {
        return Inertia::render('Websites/Show', [
            'website' => $website
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Website $website)
    {
        return Inertia::render('Websites/Edit', [
            'website' => $website
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Website $website)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Website $website)
    {
        //
    }
}
