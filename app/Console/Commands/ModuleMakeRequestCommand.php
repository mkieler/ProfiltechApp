<?php

namespace App\Console\Commands;

use App\Console\Concerns\InteractsWithModules;
use App\Support\Stubs\StubHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleMakeRequestCommand extends Command
{
    use InteractsWithModules;

    protected $signature = 'make:request {name : The request name}';
    protected $description = 'Create a new form request within a module';

    public function handle(): int
    {
        $requestName = $this->argument('name');
        $moduleName = $this->askForModule();

        if (!$moduleName) {
            return self::FAILURE;
        }

        $modulePath = StubHelper::getModulePath($moduleName);
        $requestPath = "{$modulePath}/Http/Requests/{$requestName}.php";

        if (File::exists($requestPath)) {
            $this->error("Request {$requestName} already exists in module {$moduleName}!");
            return self::FAILURE;
        }

        $content = $this->getRequestStub($moduleName, $requestName);
        File::put($requestPath, $content);

        $this->components->info("Request [{$requestPath}] created successfully.");

        return self::SUCCESS;
    }

    protected function getRequestStub(string $moduleName, string $requestName): string
    {
        return StubHelper::populate(
            StubHelper::getStubPath('request.stub'),
            [
                'namespace' => "Modules\\{$moduleName}\\Http\\Requests",
                'class' => $requestName,
            ]
        );
    }
}
