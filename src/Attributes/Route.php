<?php

namespace Plutuss\Attributes;

#[\Attribute]
class Route
{
    public function __construct(
        public string $path,
        public string $method = 'get',
        public string $name = '',
        public string $prefix = '',
    )
    {
    }
}
