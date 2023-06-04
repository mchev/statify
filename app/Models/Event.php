<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'website_id',
        'name',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function visitors()
    {
        return $this->belongsToMany(Visitor::class);
    }

    public function scopeDateRange($query, array $dates)
    {
        $query->whereBetween('created_at', [$dates[0], $dates[1]]);
    }

    public function scopeGroupByGranularity($query, string $granularity)
    {
        $query->selectRaw($granularity.' AS date, COUNT(*) AS count, name')
            ->groupBy('date', 'name');
    }
}
