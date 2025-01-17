<?php

namespace Backpack\NewsCRUD;

use Backpack\NewsCRUD\app\Models\Article;
use Backpack\NewsCRUD\app\Models\Category;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class NewsCRUDServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Where the route file lives, both inside the package and in the app (if overwritten).
     *
     * @var string
     */
    public $routeFilePath = '/routes/backpack/newscrud.php';

    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return [
            Article::class => config('backpack.newscrud.policies.article'),
            Category::class => config('backpack.newscrud.policies.category'),
            Tag::class => config('backpack.newscrud.policies.tag'),
        ];
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // publish migrations
        $this->publishes([__DIR__ . '/database/migrations' => database_path('migrations')], 'migrations');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'backpack.newscrud');
        // register its dependencies
        $this->app->register(\Cviebrock\EloquentSluggable\ServiceProvider::class);

        // setup the routes
        $this->setupRoutes($this->app->router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        // by default, use the routes file provided in vendor
        $routeFilePathInUse = __DIR__ . $this->routeFilePath;

        // but if there's a file with the same name in routes/backpack, use that one
        if (file_exists(base_path() . $this->routeFilePath)) {
            $routeFilePathInUse = base_path() . $this->routeFilePath;
        }

        $this->loadRoutesFrom($routeFilePathInUse);
    }
}
