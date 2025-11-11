<?php

declare(strict_types=1);

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

        if ($modules === []) {
            $this->error('No modules found!');
            $this->info('Run: php artisan make:module <name>');

            return null;
        }

        $choice = $this->choice(
            'Which module should this belong to?',
            $modules
        );

        return is_string($choice) ? $choice : null;
    }
}
