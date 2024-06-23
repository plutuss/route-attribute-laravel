<?php

namespace Plutuss\Service\Route;

use Plutuss\Attributes\Middleware;
use Plutuss\Attributes\Route;
use Plutuss\Attributes\RouteGroup;

class RouteAttribute extends RouteAttributeDispatcher
{

    /**
     * @param array $controllerAttributes
     * @return void
     * @throws \ReflectionException
     * @throws \Exception
     */
    public static function registerRoutesFromControllerAttributes(array $controllerAttributes = []): void
    {

        foreach ($controllerAttributes ?: static::getClassesInNamespace(static::getControllersPath()) as $controllerAttribute) {

            if (empty($controllerAttribute)) {
                continue;
            }

            $reflection = new \ReflectionClass(new $controllerAttribute);

            foreach ($reflection->getMethods() as $method) {

                $attributesRoute = $method->getAttributes(Route::class);
                $attributeMiddleware = $method->getAttributes(Middleware::class);
                $routeGroup = $reflection->getAttributes(RouteGroup::class);

                if (!empty($routeGroup)) {
                    static::setRegisterGroupRoutes($routeGroup,
                        $attributesRoute,
                        $controllerAttribute,
                        $method,
                        $attributeMiddleware,
                        $reflection);
                } else {
                    static::setRegisterRoutes($attributesRoute,
                        $controllerAttribute,
                        $method,
                        $attributeMiddleware,
                        $reflection);
                }
            }
        }
    }
}
