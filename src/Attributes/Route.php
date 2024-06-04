<?php

namespace Plutuss\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(
        public string $uri,
        public string $method = 'get',
        public string $name = '',
        public string $prefix = '',
    )
    {
    }
}
