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

use Plutuss\Models\Page;
use Plutuss\Http\Requests\StorePageRequest;
use Plutuss\Http\Requests\StorePageItemRequest;

class PageController extends Controller
{
    #[Route('/')]
    public function index()
    {
         return view('welcome')
    }
    
}

```

