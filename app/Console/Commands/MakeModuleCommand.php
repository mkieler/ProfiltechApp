<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name : The name of the module}';
    protected $description = 'Create a new module with standard DDD structure';

    public function handle(): int
    {
        $moduleName = $this->argument('name');
        $modulePath = base_path("modules/{$moduleName}");

        if (File::exists($modulePath)) {
            $this->error("Module {$moduleName} already exists!");
            return self::FAILURE;
        }

        // Create module directory structure
        $directories = [
            'Models',
            'Http/Controllers',
            'Http/Requests',
            'Database/Migrations',
            'Database/Factories',
            'Database/Seeders',
            'Providers',
            'Routes',
            'Services',
        ];

        foreach ($directories as $directory) {
            File::makeDirectory("{$modulePath}/{$directory}", 0755, true);
        }

        // Create module service provider
        $providerContent = $this->getProviderStub($moduleName);
        File::put("{$modulePath}/Providers/{$moduleName}ServiceProvider.php", $providerContent);

        // Create routes file
        $routesContent = $this->getRoutesStub($moduleName);
        File::put("{$modulePath}/Routes/web.php", $routesContent);

        $this->info("Module {$moduleName} created successfully!");
        $this->info("Don't forget to register Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider::class in bootstrap/providers.php");

        return self::SUCCESS;
    }

    protected function getProviderStub(string $moduleName): string
    {
        return $this->populateStub(
            base_path('stubs/module.provider.stub'),
            [
                'namespace' => "Modules\\{$moduleName}\\Providers",
                'class' => "{$moduleName}ServiceProvider",
            ]
        );
    }

    protected function getRoutesStub(string $moduleName): string
    {
        return $this->populateStub(
            base_path('stubs/module.routes.stub'),
            [
                'module' => $moduleName,
            ]
        );
    }

    protected function populateStub(string $stubPath, array $replacements): string
    {
        $stub = File::get($stubPath);

        foreach ($replacements as $key => $value) {
            $stub = str_replace("{{ {$key} }}", $value, $stub);
        }

        return $stub;
    }
}
