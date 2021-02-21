<?php
declare(strict_types=1);

namespace FreeRouter\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
class Method
{

    public function __construct($method) {
        $this->method = $method;
    }

    private int $method;
}