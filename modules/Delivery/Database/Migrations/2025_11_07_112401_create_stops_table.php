<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Delivery\Enums\DeliveryStopStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Modules\Delivery\Models\Route::class);
            $table->foreignIdFor(Modules\Order\Models\Order::class);
            $table->string('status')->default(DeliveryStopStatus::PENDING);
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
