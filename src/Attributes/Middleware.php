<?php

namespace Plutuss\Attributes;

use Attribute;

#[Attribute]
class Middleware
{
    public function __construct(
        public array|string $middleware,
    )
    {
    }
}
