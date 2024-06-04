## Installed packages

## Laravel:
- [GitHub](https://github.com/plutuss/route-attribute-laravel).

 
```shell
 composer require plutuss/route-attribute-laravel
```

```shell
php artisan vendor:publish --provider="Plutuss\Providers\RouteAttributeServiceProvider"
```


```php
<?php

use Plutuss\Attributes\Route;

class PageController extends Controller
{
    #[Route('/')]
    public function index()
    {
         return view('welcome')
    }
    
}

```

