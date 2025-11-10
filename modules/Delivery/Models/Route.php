<?php

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Delivery\Models\traits\InteractsWithOpenRoute;

class Route extends Model
{
    use HasFactory, InteractsWithOpenRoute;

    protected $fillable = [];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }

    protected static function newFactory()
    {
        return \Modules\Delivery\Database\Factories\RouteFactory::new();
    }

    public function totalTime()
    {
        return $this->stops->sum('time_to_next');
    }

    public function stops()
    {
        return $this->hasMany(Stop::class, 'route_id', 'id')->orderBy('sequence');
    }
}
