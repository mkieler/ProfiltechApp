<?php

declare(strict_types=1);

namespace Modules\Wordpress\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Illuminate\Database\Eloquent\Factories\Factory<static>> */
    use HasFactory;

    protected $fillable = [];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }
}
