<?php

declare(strict_types=1);

namespace Modules\User\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class UserServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'User';

    protected string $nameLower = 'user';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->configurePublishing();
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $migrationPath = module_path($this->name, 'database/migrations');
        if (! is_string($migrationPath) || $migrationPath === '') {
            return;
        }

        $this->loadMigrationsFrom($migrationPath);

        Gate::before(function ($user, $ability): ?bool {
            if (! is_object($user) || ! method_exists($user, 'hasRole')) {
                return null;
            }

            return $user->hasRole('Super Admin') ? true : null;
        });

        Relation::enforceMorphMap([
            'user' => 'Modules\User\Models\User',
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(FortifyServiceProvider::class);
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);
        $moduleLangPath = module_path($this->name, 'lang');
        if (! is_string($moduleLangPath) || $moduleLangPath === '') {
            return;
        }

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom($moduleLangPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($moduleLangPath);
        }
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');
        if (! is_string($sourcePath) || $sourcePath === '') {
            return;
        }

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        $componentPath = config('modules.paths.generator.component-class.path');
        if (! is_string($componentPath) || $componentPath === '') {
            return;
        }

        $componentNamespace = $this->module_namespace($this->name, $this->app_path($componentPath));
        Blade::componentNamespace($componentNamespace, $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands();
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Configure the publishable resources offered by the package.
     */
    protected function configurePublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../stubs/fortify.php' => config_path('fortify.php'),
            ], 'durrbar-fortify-config');

            $this->publishes([
                __DIR__.'/../../stubs/sanctum.php' => config_path('sanctum.php'),
            ], 'durrbar-sanctum-config');

            $this->publishes([
                __DIR__.'/../../stubs/permission.php' => config_path('permission.php'),
            ], 'durrbar-permission-config');
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $relativeConfigPath = config('modules.paths.generator.config.path');
        if (! is_string($relativeConfigPath) || $relativeConfigPath === '') {
            return;
        }

        $configPath = module_path($this->name, $relativeConfigPath);
        if (! is_string($configPath) || $configPath === '') {
            return;
        }

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if (! $file instanceof SplFileInfo) {
                    continue;
                }

                if ($file->isFile() && $file->getExtension() === 'php') {
                    $relativePath = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $configKey = $this->nameLower.'.'.str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $relativePath);
                    $key = ($relativePath === 'config.php') ? $this->nameLower : $configKey;

                    $this->publishes([$file->getPathname() => config_path($relativePath)], 'config');
                    $this->mergeConfigFrom($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * @return array<int, string>
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        $configuredViewPaths = config('view.paths');

        if (! is_array($configuredViewPaths)) {
            return $paths;
        }

        foreach ($configuredViewPaths as $path) {
            if (! is_string($path)) {
                continue;
            }

            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }
}
