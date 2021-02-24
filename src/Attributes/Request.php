<?php
declare(strict_types=1);

namespace FreeRouter\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
class Request
{

    private string $path;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function getPath(): string {
        return $this->path;
    }
}