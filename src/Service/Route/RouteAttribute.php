<?php

namespace Plutuss\Service\Route;

use Plutuss\Attributes\Middleware;
use Plutuss\Attributes\Route;
use Illuminate\Support\Facades\Route as IlluminateRoute;
use Symfony\Component\Finder\Finder;

class RouteAttribute
{

    /**
     * @param array $controllerAttributes
     * @return void
     * @throws \ReflectionException
     */
    public static function registerRoutesFromControllerAttributes(array $controllerAttributes = []): void
    {

        foreach ($controllerAttributes ?: self::getClassesInNamespace(app_path('Http/Controllers/')) as $controllerAttribute) {
            $reflection = new \ReflectionClass(new $controllerAttribute);

            foreach ($reflection->getMethods() as $method) {

                $attributes = $method->getAttributes(Route::class);
                $attributeMiddleware = $method->getAttributes(Middleware::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();

                    self::registerRoutes($route, $controllerAttribute, $method, $attributeMiddleware);

                }
            }
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
        $routeFacade = $routeFacade::{$route->method}($route->path, [$controllerAttribute, $method->getName()]);
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

        foreach (self::finderFiles($directory) as $finderFile) {
            [$name, $fileExtension] = explode('.', $finderFile->getFilename());

            if (in_array($name, config('route-attribute.exception_controllers'))) {
                continue;
            }

            $filenames[] = self::getClass($name);
        }

        return $filenames;
    }

    /**
     * @param string $directory
     * @return Finder
     */
    private static function finderFiles(string $directory)
    {
        return Finder::create()
            ->files()
            ->in($directory)
            ->name('*.php');
    }

    /**
     * @param string $nameClass
     * @return string
     * @throws \Exception
     */
    private static function getClass(string $nameClass): string
    {
        $namespace = config('route-attribute.namespace');

        if (is_string($namespace)) {
            return $namespace . $nameClass;
        }

        foreach ($namespace as $path) {
            if (class_exists($path . $nameClass)) {
                return $path . $nameClass;
            }
        }

        throw new \Exception("Class {$path}{$nameClass} not found");

    }
}
