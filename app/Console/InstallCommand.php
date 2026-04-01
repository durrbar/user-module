<?php

namespace Modules\User\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'durrbar:user-install')]
class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'durrbar:user-install';

    /**
     * The console command description.
     */
    protected $description = 'Install the necessary configurations, migrations, and resources for the user module';

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
