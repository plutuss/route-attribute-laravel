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

```php

<?php

use Plutuss\Attributes\Route;
use Plutuss\Attributes\Middleware;

#[Middleware('auth')] // or   #[Middleware(['auth',...])]
class UserController extends Controller
{

    #[Route(path: 'users/create', method: 'get', name: 'users.create')]  
    public function create()
    {
         return view('users.create')
    }
    
}
```


```php

<?php

use Plutuss\Attributes\Route;
use Plutuss\Attributes\RouteGroup;

#[RouteGroup(middleware: 'web', prefix: 'dashboard', subdomain: '{account}.example.com', routeNamePrefix: 'dashboard.')]
class UserController extends Controller
{

    #[Route(path: 'users/create', method: 'get', name: 'users.create')]  
    public function create()
    {
         return view('users.create')
    }
    
}
```

- API

- RouteGroup(middleware: 'api', prefix: 'api')

```php

<?php

use Plutuss\Attributes\Route;
use Plutuss\Attributes\RouteGroup;

#[RouteGroup(middleware: 'api', prefix: 'api')]
class UserController extends Controller
{

    #[Route(path: 'users',  name: 'users.index')]  
    public function index()
    {
         return response()->json([
            'users' => User::all()
        ]);
    }

    #[Route(path: 'users', method: 'post',  name: 'users.store')]  
    public function store(Request $request)
    {
         return User::create($request->all())
    }
    
}
```


