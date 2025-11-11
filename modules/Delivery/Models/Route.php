<?php

declare(strict_types=1);

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Delivery\Database\Factories\RouteFactory;
use Modules\Delivery\Models\traits\InteractsWithOpenRoute;

class Route extends Model
{
    /** @use HasFactory<RouteFactory> */
    use HasFactory;

    use InteractsWithOpenRoute;

    protected $fillable = ['name', 'origin', 'status'];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }

    protected static function newFactory(): RouteFactory
    {
        return RouteFactory::new();
    }

    public function totalTime(): mixed
    {
        return $this->stops->sum('time_to_next');
    }

    /**
     * @return HasMany<Stop, $this>
     */
    public function stops(): HasMany
    {
        return $this->hasMany(Stop::class, 'route_id', 'id')->orderBy('sequence');
    }
}
