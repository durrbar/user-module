<?php

namespace Modules\User\Console;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('durrbar:user-install')]
#[Description('Install the necessary configurations, migrations, and resources for the user module')]
class InstallCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting the installation of the Durrbar User Module...');

        $this->publishFortifyConfig();

        // You can add other installation tasks here (migrations, seeding, etc.)
        return self::SUCCESS;
    }

    /**
     * Publish Fortify config file.
     */
    private function publishFortifyConfig(): void
    {
        $this->call('vendor:publish', [
            '--tag' => ['durrbar-fortify-config', 'durrbar-sanctum-config', 'durrbar-permission-config'],
        ]);

        $this->info('User scaffolding installed successfully.');
    }
}
