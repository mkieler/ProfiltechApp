<?php

declare(strict_types=1);

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
        $modulePath = base_path('modules/' . $moduleName);

        if (File::exists($modulePath)) {
            $this->error(sprintf('Module %s already exists!', $moduleName));

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
            File::makeDirectory(sprintf('%s/%s', $modulePath, $directory), 0755, true);
        }

        // Create module service provider
        $providerContent = $this->getProviderStub($moduleName);
        File::put(sprintf('%s/Providers/%sServiceProvider.php', $modulePath, $moduleName), $providerContent);

        // Create routes file
        $routesContent = $this->getRoutesStub($moduleName);
        File::put($modulePath . '/Routes/web.php', $routesContent);

        $this->info(sprintf('Module %s created successfully!', $moduleName));
        $this->info(sprintf("Don't forget to register Modules\\%s\\Providers\\%sServiceProvider::class in bootstrap/providers.php", $moduleName, $moduleName));

        return self::SUCCESS;
    }

    protected function getProviderStub(string $moduleName): string
    {
        return $this->populateStub(
            base_path('stubs/module.provider.stub'),
            [
                'namespace' => sprintf('Modules\%s\Providers', $moduleName),
                'class' => $moduleName . 'ServiceProvider',
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
            $stub = str_replace(sprintf('{{ %s }}', $key), $value, $stub);
        }

        return $stub;
    }
}
