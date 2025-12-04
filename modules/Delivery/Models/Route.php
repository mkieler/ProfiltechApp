<?php

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Models\traits\InteractsWithOpenRoute;

class Route extends Model
{
    use HasFactory, InteractsWithOpenRoute;

    protected $fillable = ['name', 'date', 'status'];

    protected $hidden = [];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => DeliveryStatus::class,
        ];
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
