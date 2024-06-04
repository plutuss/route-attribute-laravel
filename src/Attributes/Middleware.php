<?php

namespace Plutuss\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Middleware
{
    public function __construct(
        public array|string $middleware,
    )
    {
    }
}
