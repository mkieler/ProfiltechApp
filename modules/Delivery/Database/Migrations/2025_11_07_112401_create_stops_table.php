<?php

declare(strict_types=1);

use Modules\Delivery\Models\Route;
use Modules\Order\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stops', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignIdFor(Route::class);
            $blueprint->foreignIdFor(Order::class);
            $blueprint->float('latitude')->nullable();
            $blueprint->float('longitude')->nullable();
            $blueprint->integer('sequence');
            $blueprint->integer('service_time')->default(300);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stops');
    }
};
