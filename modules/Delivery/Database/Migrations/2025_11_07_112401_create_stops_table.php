<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Modules\Delivery\Models\Route::class);
            $table->foreignIdFor(Modules\Order\Models\Order::class);
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->integer('sequence');
            $table->integer('service_time')->default(300);
            $table->timestamps();
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
