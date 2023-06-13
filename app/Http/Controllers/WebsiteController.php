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
            Carbon::parse($request->range[1])->endOfDay(),
        ] : [
            now()->subDays(6)->startOfDay(),
            now()->endOfDay(),
        ];

        $duration = $range[0]->diffInDays($range[1]);
        $compareDuration = $duration + 1;
        $previousStart = $range[0]->copy()->subDays($compareDuration);
        $previousEnd = $range[1]->copy()->subDays($compareDuration);

        $options = [
            'years' => [
                'format' => 'Y',
                'query_format' => "DATE_FORMAT(created_at, '%Y')",
                'range' => [
                    $range[0]->copy()->startOfYear(),
                    '1 year',
                    $range[1]->copy()->endOfYear(),
                ],
            ],
            'months' => [
                'format' => 'Y-m',
                'query_format' => "DATE_FORMAT(created_at, '%Y-%m')",
                'range' => [
                    $range[0]->copy()->startOfMonth(),
                    '1 month',
                    $range[1]->copy()->endOfMonth(),
                ],
            ],
            'days' => [
                'format' => 'Y-m-d',
                'query_format' => 'DATE(created_at)',
                'range' => [
                    $range[0]->copy()->startOfDay(),
                    '1 day',
                    $range[1]->copy()->endOfDay(),
                ],
            ],
            'hours' => [
                'format' => 'Y-m-d H',
                'query_format' => "DATE_FORMAT(created_at, '%Y-%m-%d %H')",
                'range' => [
                    $range[0]->copy()->startOfDay(),
                    '1 hour',
                    $range[1]->copy()->endOfDay(),
                ],
            ],
        ];

        $granularity = $this->determineGranularity($range);

        $period = $options[$granularity]['range'];
        $dates = collect(CarbonPeriod::create(...$period))
            ->map(fn ($date) => $date->format($options[$granularity]['format']))
            ->toArray();

        $visitors = $website->visitors()
            ->dateRange($range)
            ->groupByGranularity($options[$granularity]['query_format'])
            ->get();

        $views = $website->views()
            ->dateRange($range)
            ->groupByGranularity($options[$granularity]['query_format'])
            ->get();

        $events = $website->events()
            ->dateRange($range)
            ->groupByGranularity($options[$granularity]['query_format'])
            ->get();

        $previousVisitorsCount = $website->visitors()
            ->dateRange([$previousStart, $previousEnd])
            ->count();

        $previousViewsCount = $website->views()
            ->dateRange([$previousStart, $previousEnd])
            ->count();

        $visitors_count = $visitors->sum('count');
        $views_count = $views->sum('count');
        $singlePageVisitors = $website->visitors()->dateRange($range)->has('views', '=', 1)->count();
        $bounceRate = ($visitors_count > 0) ? ($singlePageVisitors / $visitors_count) * 100 : 0;

        $stats = [
            'summary' => [
                'visitors' => [
                    'total' => $visitors_count,
                    'diff' => $visitors_count - $previousVisitorsCount,
                ],
                'views' => [
                    'total' => $views_count,
                    'diff' => $views_count - $previousViewsCount,
                ],
                'average_time' => $visitors->avg('average_time'),
                'bounce_rate' => round($bounceRate),
                'engagement_rate' => round(100 - $bounceRate),
            ],
            'visitors' => $this->fillMissingDates($visitors->groupBy('date')->map(fn ($group) => $group->sum('count')), $dates),
            'views' => $this->fillMissingDates($views->groupBy('date')->map(fn ($group) => $group->sum('count')), $dates),
            'events' => $this->convertToDatasets($this->fillMissingDates($events->groupBy('date')->map(fn ($group) => $group->pluck('count', 'name')), $dates)),
            'browsers' => $this->generateGroupedData($visitors, 'browser'),
            'os' => $this->generateGroupedData($visitors, 'os'),
            'devices' => $this->generateGroupedData($visitors, 'device'),
            'screens' => $this->generateGroupedData($visitors, 'screen'),
            'languages' => $this->generateGroupedData($visitors, 'language'),
            'countries' => $this->generateGroupedData($visitors, 'country'),
            'cities' => $this->generateGroupedData($visitors, 'city'),
            'referers_visitors' => $this->generateGroupedData($visitors, 'referer_domain'),
            'referers_views' => $this->generateGroupedData($views, 'referer_domain'),
            'pages' => $this->generateGroupedData($views, 'url_path'),
        ];

        return Inertia::render('Websites/Show', [
            'filters' => [
                'range' => [$range[0]->format('Y-m-d'), $range[1]->format('Y-m-d')],
                'search' => $request->search,
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
            'script' => '<script async src="'.config('app.url').'/'.config('counted.script_name').'.js" website="'.$website->id.'"></script>',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Website $website)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:websites,name,' . $website->id, 'max:255', 'min:3'],
            'domain' => ['required', 'unique:websites,domain,' . $website->id, new Domain],
        ]);

        $website->update($validated);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Website $website)
    {
        $request->validate([
            'name' => ['required', 'confirmed'],
        ]);

        $website->delete();

        return redirect()->route('websites.index');
    }

    /**
     * Determine the granularity based on the range.
     */
    protected function determineGranularity($range)
    {
        $duration = $range[0]->diffInDays($range[1]);

        return match (true) {
            $duration > 365 => 'years',
            $duration > 60 => 'months',
            $duration > 1 => 'days',
            default => 'hours',
        };
    }

    /**
     * Generate grouped data based on a specific column from the data.
     */
    protected function generateGroupedData($data, $column)
    {
        return $data->groupBy($column)->map(fn ($group) => $group->sum('count'))->sortDesc();
    }

    public function convertToDatasets($eventCounts)
    {
        $eventNames = [];

        foreach ($eventCounts as $date => $events) {
            if ($events !== 0) {
                foreach ($events as $name => $count) {
                    if (! in_array($name, $eventNames)) {
                        $eventNames[] = $name;
                    }
                }
            }
        }

        $datasets = [];

        foreach ($eventNames as $eventName) {
            $data = [];

            foreach ($eventCounts as $date => $events) {
                if ($events !== 0) {
                    $count = $events->get($eventName, 0);
                } else {
                    $count = 0;
                }
                $data[] = $count;
            }

            $datasets[] = [
                'label' => $eventName,
                'data' => $data,
                'backgroundColor' => sprintf('rgba(%d, %d, %d, %.2f)', rand(0, 40), rand(100, 190), rand(100, 180), rand(0, 100) / 100),
            ];
        }

        return $datasets;
    }
}
