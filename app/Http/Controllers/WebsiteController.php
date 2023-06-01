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

        $range = $request->has('range') ? [
            Carbon::parse($request->range[0])->startOfDay(),
            Carbon::parse($request->range[1])->endOfDay()
        ] : [
            now()->subDays(6)->startOfDay(),
            now()->endOfDay()
        ];

        $from = $range[0];
        $to = $range[1];
        $duration = $range[0]->diff($range[1]);

        $options = [
            'years' => [
                'format' => "Y",
                'query_format' => "DATE_FORMAT(created_at, '%Y')",
                'range' => [
                    $range[0]->copy()->startOfYear(),
                    '1 year',
                    $range[1]->copy()->endOfYear()
                ]
            ],
            'months' => [
                'format' => "Y-m",
                'query_format' => "DATE_FORMAT(created_at, '%Y-%m')",
                'range' => [
                    $range[0]->copy()->startOfMonth(),
                    '1 month',
                    $range[1]->copy()->endOfMonth()
                ]
            ],
            'days' => [
                'format' => "Y-m-d",
                'query_format' => "DATE(created_at)",
                'range' => [
                    $range[0]->copy()->startOfDay(),
                    '1 day',
                    $range[1]->copy()->endOfDay()
                ]
            ],
            'hours' => [
                'format' => "Y-m-d H",
                'query_format' => "DATE_FORMAT(created_at, '%Y-%m-%d %H')",
                'range' => [
                    $range[0]->copy()->startOfDay(),
                    '1 hour',
                    $range[1]->copy()->endOfDay()
                ]
            ],
        ];

        $granularity = ($duration->days > 365) ? 'years' : // At least one year
                        (($duration->days > 60) ? 'months' : // At least two months
                        (($duration->days > 1) ? 'days' : 'hours'));

        $period = $options[$granularity]['range'];
        $dates = collect(CarbonPeriod::create(...$period))
            ->map(fn ($date) => $date->format($options[$granularity]['format']))
            ->toArray();

        $visitors = $website->visitors()
            ->range($range)
            ->selectRaw($options[$granularity]['query_format'] . " AS date, COUNT(*) AS count, browser, os, device, screen, language, country, city")
            ->groupBy('date', 'browser', 'os', 'device', 'screen', 'language', 'country', 'city')
            ->get();

        $views = $website->views()
            ->range($range)
            ->selectRaw($options[$granularity]['query_format'] . " AS date, COUNT(*) AS count, url_path, referer_domain, page_title")
            ->groupBy('date', 'url_path', 'referer_domain', 'page_title')
            ->get();

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
            'filters' => [
                'range' => [$range[0]->format('Y-m-d'), $range[1]->format('Y-m-d')],
                'search' => $request->search
            ],
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
            'script' => '<script src="' . config('app.url') . '/"' . config('satify.script_name') . ' >'
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
