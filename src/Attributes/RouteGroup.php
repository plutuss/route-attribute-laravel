<?php

namespace Plutuss\Attributes;

use Attribute;

#[Attribute(\Attribute::TARGET_CLASS)]
class RouteGroup
{

    public function __construct(
        public string|array|null $middleware = null,
        public string|null       $subdomain = null,
        public string|null       $prefix = null,
        public string|null       $routeNamePrefix = null,

    )
    {
    }

}
