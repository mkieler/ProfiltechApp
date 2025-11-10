<?php

namespace App\Console\Concerns;

use App\Support\Stubs\StubHelper;

trait InteractsWithModules
{
    /**
     * Ask the user to select a module.
     */
    protected function askForModule(): ?string
    {
        $modules = StubHelper::getAvailableModules();

        if (empty($modules)) {
            $this->error('No modules found!');
            $this->info('Run: php artisan make:module <name>');
            return null;
        }

        $moduleName = $this->choice(
            'Which module should this belong to?',
            $modules
        );

        return $moduleName;
    }
}
