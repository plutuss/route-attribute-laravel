<?php

namespace Plutuss\Providers;

use Plutuss\Service\Route\RouteAttribute;
use Illuminate\Support\ServiceProvider;

class RouteAttributeServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    /**
     * @throws \ReflectionException
     */
    public function boot(): void
    {
        RouteAttribute::registerRoutesFromControllerAttributes();

        $this->publishes([
            __DIR__ . '/../config/route-attribute.php' => config_path('route-attribute.php'),
        ]);
    }
}
