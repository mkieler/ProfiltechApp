<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Concerns\InteractsWithModules;
use App\Support\Stubs\StubHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleMakeModelCommand extends Command
{
    use InteractsWithModules;

    protected $signature = 'make:model {name : The model name} {--m|migration : Create a migration file}';

    protected $description = 'Create a new model within a module';

    public function handle(): int
    {
        $modelName = $this->argument('name');
        $moduleName = $this->askForModule();

        if (! $moduleName) {
            return self::FAILURE;
        }

        $modulePath = StubHelper::getModulePath($moduleName);
        $modelPath = sprintf('%s/Models/%s.php', $modulePath, $modelName);

        if (File::exists($modelPath)) {
            $this->error(sprintf('Model %s already exists in module %s!', $modelName, $moduleName));

            return self::FAILURE;
        }

        $content = $this->getModelStub($moduleName, $modelName);
        File::put($modelPath, $content);

        $this->components->info(sprintf('Model [%s] created successfully.', $modelPath));

        // Create migration if requested
        if ($this->option('migration')) {
            $tableName = Str::snake(Str::pluralStudly($modelName));
            $migrationName = sprintf('create_%s_table', $tableName);

            $this->newLine();

            // Get the migration command and set the module before running
            $application = $this->getApplication();
            if ($application !== null) {
                $migrationCommand = $application->find('make:migration');
                if (method_exists($migrationCommand, 'setModule')) {
                    $migrationCommand->setModule($moduleName);
                }
            }

            $this->call('make:migration', [
                'name' => $migrationName,
            ]);
        }

        return self::SUCCESS;
    }

    protected function getModelStub(string $moduleName, string $modelName): string
    {
        return StubHelper::populate(
            StubHelper::getStubPath('model.stub'),
            [
                'namespace' => sprintf('Modules\%s\Models', $moduleName),
                'class' => $modelName,
            ]
        );
    }
}
