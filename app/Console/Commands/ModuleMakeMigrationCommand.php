<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Concerns\InteractsWithModules;
use App\Support\Stubs\StubHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleMakeMigrationCommand extends Command
{
    use InteractsWithModules;

    protected $signature = 'make:migration {name : The migration name}';

    protected $description = 'Create a new migration within a module';

    private ?string $selectedModule = null;

    public function handle(): int
    {
        $migrationName = $this->argument('name');
        $moduleName = $this->getModuleName();

        if (! $moduleName) {
            return self::FAILURE;
        }

        $modulePath = StubHelper::getModulePath($moduleName);

        // Generate timestamp for migration
        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_{$migrationName}.php";
        $migrationPath = "{$modulePath}/Database/Migrations/{$fileName}";

        if (File::exists($migrationPath)) {
            $this->error("Migration {$migrationName} already exists in module {$moduleName}!");

            return self::FAILURE;
        }

        $content = $this->getMigrationStub($migrationName);
        File::put($migrationPath, $content);

        $this->components->info("Migration [{$migrationPath}] created successfully.");

        return self::SUCCESS;
    }

    protected function getModuleName(): ?string
    {
        // If module was set programmatically (e.g., from make:model -m), use it
        if ($this->selectedModule) {
            return $this->selectedModule;
        }

        return $this->askForModule();
    }

    public function setModule(string $moduleName): void
    {
        $this->selectedModule = $moduleName;
    }

    protected function getMigrationStub(string $migrationName): string
    {
        // Extract table name from migration name
        $tableName = $this->extractTableName($migrationName);

        return StubHelper::populate(
            StubHelper::getStubPath('migration.stub'),
            [
                'table' => $tableName,
            ]
        );
    }

    protected function extractTableName(string $migrationName): string
    {
        // Try to extract table name from migration name
        // e.g., create_users_table -> users
        if (preg_match('/create_(.+)_table/', $migrationName, $matches)) {
            return $matches[1];
        }

        // Default to migration name
        return Str::snake($migrationName);
    }
}
