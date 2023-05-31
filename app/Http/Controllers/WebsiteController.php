<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Rules\Domain;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
    public function show(Request $request, Website $website)
    {
        if (! $request->has('range')) {
            $request->merge(['range' => [now()->subDays(6)->startOfDay(), now()->endOfDay()]]);
        }

        $visitors = $website->visitors()
            ->range($request->range)
            ->selectRaw('DATE(created_at) AS date, COUNT(*) AS count, browser, os, device, screen, language, country, city')
            ->groupBy('date', 'browser', 'os', 'device', 'screen', 'language', 'country', 'city')
            ->get();

        $views = $website->views()
            ->range($request->range)
            ->selectRaw('DATE(created_at) AS date, COUNT(*) AS count, url_path, referer_domain, page_title')
            ->groupBy('date', 'url_path', 'referer_domain', 'page_title')
            ->get();

        $dates = collect(CarbonPeriod::create(Carbon::parse($request->range[0])->startOfDay(), Carbon::parse($request->range[1])->endOfDay()))
            ->map(fn ($date) => $date->format('Y-m-d'))
            ->toArray();

        $stats = [
            'counts' => [
                'visitors_count' => $visitors->first()?->count,
                'views_count' => $views->first()?->count,
            ],
            'visitors' => $this->fillMissingDates($visitors->groupBy('date')->map(fn ($group) => $group->sum('count')), $dates),
            'views' => $this->fillMissingDates($views->groupBy('date')->map(fn ($group) => $group->sum('count')), $dates),
            'browsers' => $visitors->groupBy('browser')->map(fn ($group) => $group->sum('count'))->sortDesc(),
            'os' => $visitors->groupBy('os')->map(fn ($group) => $group->sum('count'))->sortDesc(),
            'devices' => $visitors->groupBy('device')->map(fn ($group) => $group->sum('count'))->sortDesc(),
            'screens' => $visitors->groupBy('screen')->map(fn ($group) => $group->sum('count'))->sortDesc(),
            'languages' => $visitors->groupBy('language')->map(fn ($group) => $group->sum('count'))->sortDesc(),
            'countries' => $visitors->groupBy('country')->map(fn ($group) => $group->sum('count'))->sortDesc(),
            'cities' => $visitors->groupBy('city')->map(fn ($group) => $group->sum('count'))->sortDesc(),
            'referers_visitors' => $visitors->groupBy('referer_domain')->map(fn ($group) => $group->sum('count'))->sortDesc(),
            'referers_views' => $views->groupBy('referer_domain')->map(fn ($group) => $group->sum('count'))->sortDesc(),
            'pages' => $views->groupBy('url_path')->map(fn ($group) => $group->sum('count'))->sortDesc(),
        ];

        return Inertia::render('Websites/Show', [
            'filters' => $request->all('range', 'search'),
            'website' => $website->load('team'),
            'dates' => $dates,
            'stats' => $stats,
        ]);
    }

    protected function fillMissingDates($data, $dates)
    {
        $filled = collect($dates)->mapWithKeys(function ($date) use ($data) {
            return [$date => $data->get($date, 0)];
        });

        return $filled;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Website $website)
    {
        return Inertia::render('Websites/Edit', [
            'website' => $website,
            'script' => '<script src="' . config('app.url') . '>'
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
