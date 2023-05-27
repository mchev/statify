<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'website_id',
        'type',
        'url_path',
        'url_query',
        'referer_path',
        'referer_query',
        'referer_domain',
        'page_title',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function visitors()
    {
        return $this->belongsToMany(Visitor::class);
    }

}
