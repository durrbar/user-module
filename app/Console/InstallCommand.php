<?php

namespace Modules\User\Console;

use Illuminate\Console\Command;

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
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting the installation of the Durrbar User Module...');

        $this->publishFortifyConfig();

        // You can add other installation tasks here (migrations, seeding, etc.)
    }

    /**
     * Publish Fortify config file.
     */
    private function publishFortifyConfig()
    {
        $this->info('Publishing Fortify configuration...');
        $this->callSilent('vendor:publish', [
            '--provider' => 'Laravel\Fortify\FortifyServiceProvider',
            '--tag' => 'config',
        ]);

        $this->info('Fortify configuration published successfully.');
    }
}
