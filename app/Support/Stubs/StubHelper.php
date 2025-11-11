<?php

declare(strict_types=1);

namespace App\Support\Stubs;

use Illuminate\Support\Facades\File;

class StubHelper
{
    /**
     * Populate a stub file with replacements.
     *
     * @param array<string, string> $replacements
     */
    public static function populate(string $stubPath, array $replacements): string
    {
        $stub = File::get($stubPath);

        foreach ($replacements as $key => $value) {
            $stub = str_replace(sprintf('{{ %s }}', $key), $value, $stub);
        }

        return $stub;
    }

    /**
     * Get the stub path from the base stubs directory.
     */
    public static function getStubPath(string $stubName): string
    {
        return base_path('stubs/' . $stubName);
    }

    /**
     * Check if a module exists.
     */
    public static function moduleExists(string $moduleName): bool
    {
        return File::exists(base_path('modules/' . $moduleName));
    }

    /**
     * Get all available modules.
     *
     * @return array<int, string>
     */
    public static function getAvailableModules(): array
    {
        $modulesPath = base_path('modules');

        if (! File::exists($modulesPath)) {
            return [];
        }

        /** @var array<int, string> */
        return collect(File::directories($modulesPath))
            ->map(fn ($path): string => basename((string) $path))
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Get the module path.
     */
    public static function getModulePath(string $moduleName): string
    {
        return base_path('modules/' . $moduleName);
    }
}
