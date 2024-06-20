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
use Plutuss\Attributes\Middleware;

class UserController extends Controller
{
    #[Route(uri: '/users', name: 'users.index')]
    public function index()
    {
         return view('users.index')
    }
    
    #[Route(path: 'users/create', method: 'get', name: 'users.create')]
    #[Middleware('auth')] // or   #[Middleware(['auth',...])]
    public function create()
    {
         return view('users.create')
    }
    
    #[Route(path: 'users/create', method: 'post', name: 'users.store')]
    public function store()
    {
     //
    }
    
    #[Route(path: 'users/{user}', name: 'users.show')]
    public function show(\App\Models\User $user)
    {
     //
    }
    
}

```

