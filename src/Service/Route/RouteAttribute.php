<?php


namespace Plutuss\Service\Route;


use Plutuss\Attributes\Middleware;
use Plutuss\Attributes\Route;
use Illuminate\Support\Facades\Route as IlluminateRoute;
use Symfony\Component\Finder\Finder;

class RouteAttribute
{

    public static function registerRoutesFromControllerAttributes(array $controllerAttributes = []): void
    {

        foreach ($controllerAttributes ?: self::getClassesInNamespace(app_path('Http/Controllers/')) as $controllerAttribute) {
            $reflection = new \ReflectionClass(new $controllerAttribute);

            foreach ($reflection->getMethods() as $method) {

                $attributes = $method->getAttributes(Route::class);
                $attributeMiddleware = $method->getAttributes(Middleware::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $routeFacade = IlluminateRoute::class;
                    $routeFacade = $routeFacade::{$route->method}($route->path, [$controllerAttribute, $method->getName()]);
                    $routeFacade->name($route->name);
                    $routeFacade->prefix($route->prefix);
                    foreach ($attributeMiddleware as $middleware) {
                        $routeFacade->middleware($middleware?->newInstance()->middleware ?: '');
                    }

                }
            }
        }
    }

    public static function getClassesInNamespace(string $directory): array
    {
        $finderFiles = Finder::create()
            ->files()
            ->in($directory)
            ->name('*.php');

        $filenames = [];

        foreach ($finderFiles as $finderFile) {
            [$name, $fileExtension] = explode('.', $finderFile->getFilename());

            if ($name == 'Controller') {
                continue;
            }

            $filenames[] = "App\Http\Controllers\\" . $name;
        }

        return $filenames;
    }
}
