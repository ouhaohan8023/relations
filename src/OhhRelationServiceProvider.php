<?php

namespace Ohh\Relation;

use Illuminate\Support\ServiceProvider;

class OhhRelationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__.'/config/relationship.php' => config_path('relationship.php'),
            ],
            'relation-config'
        );

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
