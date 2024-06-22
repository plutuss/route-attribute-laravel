<?php

namespace Plutuss\Service\Route;

use Plutuss\Attributes\Middleware;
use Plutuss\Attributes\Route;
use Illuminate\Support\Facades\Route as IlluminateRoute;
use Plutuss\Attributes\RouteGroup;
use Symfony\Component\Finder\Finder;

class RouteAttribute
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
                    foreach ($routeGroup as $group) {
                        $groupRoute = $group->newInstance();
                        $routeFacade = IlluminateRoute::class;
                        $routeFacade::middleware($groupRoute->middleware)
                            ->prefix($groupRoute->prefix)
                            ->name($groupRoute->routeNamePrefix)
                            ->domain($groupRoute->subdomain)
                            ->group(function ()
                            use ($controllerAttribute, $method, $attributeMiddleware, $attributesRoute, $reflection) {
                                static::setRegisterRoutes($attributesRoute, $controllerAttribute, $method, $attributeMiddleware, $reflection);
                            });

                    }
                } else {
                    static::setRegisterRoutes($attributesRoute, $controllerAttribute, $method, $attributeMiddleware, $reflection);
                }

            }
        }
    }

    private static function setRegisterRoutes($attributesRoute, $controllerAttribute, $method, $attributeMiddleware, $reflection): void
    {
        foreach ($attributesRoute as $attribute) {
            $route = $attribute->newInstance();
            static::registerRoutes(
                $route,
                $controllerAttribute,
                $method,
                array_merge($attributeMiddleware, $reflection->getAttributes(Middleware::class)));

        }
    }

    /**
     * @param $route
     * @param $controllerAttribute
     * @param $method
     * @param $attributeMiddleware
     * @return void
     */
    private static function registerRoutes($route, $controllerAttribute, $method, $attributeMiddleware): void
    {
        $routeFacade = IlluminateRoute::class;
        $routeFacade = $routeFacade::{$route->method}($route->uri, [$controllerAttribute, $method->getName()]);
        $routeFacade->name($route->name);
        $routeFacade->prefix($route->prefix);
        foreach ($attributeMiddleware as $middleware) {
            $routeFacade->middleware($middleware?->newInstance()->middleware ?: '');
        }
    }


    /**
     * @param string $directory
     * @return array
     * @throws \Exception
     */
    private static function getClassesInNamespace(string $directory): array
    {

        $filenames = [];

        foreach (static::finderFiles($directory) as $finderFile) {
            [$name, $fileExtension] = explode('.', $finderFile->getFilename());

            if (in_array($name, static::getListExceptionControllers())) {
                continue;
            }

            $filenames[] = static::getClass($name);
        }

        return $filenames;
    }


    /**
     * @param string $directory
     * @return Finder
     */
    private static function finderFiles(string $directory): Finder
    {
        return Finder::create()
            ->files()
            ->in($directory)
            ->name('*.php');
    }

    /**
     * @param string $nameClass
     * @return string|null
     * @throws \Exception
     */
    private static function getClass(string $nameClass): string|null
    {
        try {

            $namespace = static::getNamespaces();

            if (is_string($namespace)) {
                return $namespace . $nameClass;
            }

            foreach ($namespace as $path) {
                if (class_exists($path . $nameClass)) {
                    return $path . $nameClass;
                }
            }

            return null;
        } catch (\Exception $exception) {
            throw new \Exception("Class {$nameClass} not found");
        }
    }

    /**
     * @return array|string
     */
    private static function getNamespaces(): array|string
    {
        return config('route-attribute.namespace') ?: [];
    }

    /**
     * @return array
     */
    private static function getListExceptionControllers(): array
    {
        return config('route-attribute.exception_controllers') ?: [];
    }

    /**
     * @return string
     */
    private static function getControllersPath(): string
    {
        return app_path('Http/Controllers/');
    }

}
