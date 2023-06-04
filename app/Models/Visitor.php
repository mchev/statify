<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token',
        'website_id',
        'browser',
        'os',
        'device',
        'screen',
        'language',
        'country',
        'city',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function scopeDateRange($query, array $dates)
    {
        $query->whereBetween('created_at', [$dates[0], $dates[1]]);
    }

    public function scopeGroupByGranularity($query, string $granularity)
    {
        $query->selectRaw($granularity . " AS date, COUNT(*) AS count, AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as average_time, browser, os, device, screen, language, country, city")
            ->groupBy('date', 'browser', 'os', 'device', 'screen', 'language', 'country', 'city');
    }
}
