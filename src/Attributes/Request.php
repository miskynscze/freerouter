<?php


namespace FreeRouter\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
class Request
{

    public function __construct(string $path) {
        $this->path = $path;
    }

    private string $path;
}