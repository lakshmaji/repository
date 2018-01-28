<?php

namespace Lakshmaji\Repository;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;
use Lakshmaji\Repository\Console\Commands\Creators\CriteriaCreator;
use Lakshmaji\Repository\Console\Commands\Creators\RepositoryCreator;
use Lakshmaji\Repository\Console\Commands\MakeCriteriaCommand;
use Lakshmaji\Repository\Console\Commands\MakeRepositoryCommand;

/**
 * RepositoryServiceProvider
 *
 * @author     lakshmaji 
 * @package    Repository
 * @version    1.0.0
 * @since      Class available since Release 1.0.0
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /** Indicates if loading of the provider is deferred. @var bool */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $config_path = __DIR__ . '/config/repository.php';

        $this->publishes(
            [$config_path => config_path('repository.php')],
            'repository'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->registerMakeRepositoryCommand();
        $this->registerMakeCriteriaCommand();
        $this->commands(['command.repository.make', 'command.criteria.make']);

        $config_path = __DIR__ . '/config/repository.php';

        $this->mergeConfigFrom(
            $config_path,
            'repository'
        );
    }

    /**
     * Register the bindings.
     */
    protected function registerBindings()
    {
        $this->app->instance('FileSystem', new Filesystem());
        $this->app->bind('Composer', function ($app) {
            return new Composer($app['FileSystem']);
        });

        $this->app->singleton('RepositoryCreator', function ($app) {
            return new RepositoryCreator($app['FileSystem']);
        });

        $this->app->singleton('CriteriaCreator', function ($app) {
            return new CriteriaCreator($app['FileSystem']);
        });
    }

    /**
     * Register the make:repository command.
     */
    protected function registerMakeRepositoryCommand()
    {
        if (method_exists(\Illuminate\Foundation\Application::class, 'singleton')) {
            $this->app->singleton('command.repository.make', function ($app) {
                return new MakeRepositoryCommand($app['RepositoryCreator'], $app['Composer']);
            });
        } else {
            $this->app['command.repository.make'] = $this->app->share(
                function ($app) {
                    return new MakeRepositoryCommand($app['RepositoryCreator'], $app['Composer']);
                }
            );
        }
    }

    /**
     * Register the make:criteria command.
     */
    protected function registerMakeCriteriaCommand()
    {
        if (method_exists(\Illuminate\Foundation\Application::class, 'singleton')) {
            $this->app->singleton('command.criteria.make', function ($app) {
                return new MakeCriteriaCommand($app['CriteriaCreator'], $app['Composer']);
            });
        } else {
            $this->app['command.criteria.make'] = $this->app->share(
                function ($app) {
                    return new MakeCriteriaCommand($app['CriteriaCreator'], $app['Composer']);
                }
            );
        }

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.repository.make',
            'command.criteria.make',
        ];
    }
}
// end of class RepositoryServiceProvider
// end of file RepositoryServiceProvider.php
