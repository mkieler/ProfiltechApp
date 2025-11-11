<?php

declare(strict_types=1);

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

        if (! $moduleName) {
            return self::FAILURE;
        }

        $modulePath = StubHelper::getModulePath($moduleName);
        $requestPath = sprintf('%s/Http/Requests/%s.php', $modulePath, $requestName);

        if (File::exists($requestPath)) {
            $this->error(sprintf('Request %s already exists in module %s!', $requestName, $moduleName));

            return self::FAILURE;
        }

        $content = $this->getRequestStub($moduleName, $requestName);
        File::put($requestPath, $content);

        $this->components->info(sprintf('Request [%s] created successfully.', $requestPath));

        return self::SUCCESS;
    }

    protected function getRequestStub(string $moduleName, string $requestName): string
    {
        return StubHelper::populate(
            StubHelper::getStubPath('request.stub'),
            [
                'namespace' => sprintf('Modules\%s\Http\Requests', $moduleName),
                'class' => $requestName,
            ]
        );
    }
}
