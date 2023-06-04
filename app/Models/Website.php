<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'domain',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

}
