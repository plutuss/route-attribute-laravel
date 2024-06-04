<?php

namespace Plutuss\Attributes;

#[\Attribute]
class Middleware
{
    public function __construct(
        public array|string $middleware,
    )
    {
    }
}
