<?php

declare(strict_types=1);

namespace App\Providers;

use App\Console\Commands\MakeModuleCommand;
use App\Console\Commands\ModuleMakeControllerCommand;
use App\Console\Commands\ModuleMakeMigrationCommand;
use App\Console\Commands\ModuleMakeModelCommand;
use App\Console\Commands\ModuleMakeRequestCommand;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModuleCommand::class,
                ModuleMakeModelCommand::class,
                ModuleMakeControllerCommand::class,
                ModuleMakeMigrationCommand::class,
                ModuleMakeRequestCommand::class,
            ]);
        }
    }
}
