<?php

namespace App\Console\Commands;

use App\Console\Concerns\InteractsWithModules;
use App\Support\Stubs\StubHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleMakeControllerCommand extends Command
{
    use InteractsWithModules;

    protected $signature = 'make:controller {name : The controller name} {--r|resource : Generate a resource controller}';
    protected $description = 'Create a new controller within a module';

    public function handle(): int
    {
        $controllerName = $this->argument('name');
        $moduleName = $this->askForModule();

        if (!$moduleName) {
            return self::FAILURE;
        }

        $modulePath = StubHelper::getModulePath($moduleName);
        $controllerPath = "{$modulePath}/Http/Controllers/{$controllerName}.php";

        if (File::exists($controllerPath)) {
            $this->error("Controller {$controllerName} already exists in module {$moduleName}!");
            return self::FAILURE;
        }

        $content = $this->option('resource')
            ? $this->getResourceControllerStub($moduleName, $controllerName)
            : $this->getControllerStub($moduleName, $controllerName);

        File::put($controllerPath, $content);

        $this->components->info("Controller [{$controllerPath}] created successfully.");

        return self::SUCCESS;
    }

    protected function getControllerStub(string $moduleName, string $controllerName): string
    {
        return StubHelper::populate(
            StubHelper::getStubPath('controller.stub'),
            [
                'namespace' => "Modules\\{$moduleName}\\Http\\Controllers",
                'class' => $controllerName,
            ]
        );
    }

    protected function getResourceControllerStub(string $moduleName, string $controllerName): string
    {
        return StubHelper::populate(
            StubHelper::getStubPath('controller.resource.stub'),
            [
                'namespace' => "Modules\\{$moduleName}\\Http\\Controllers",
                'class' => $controllerName,
            ]
        );
    }
}
