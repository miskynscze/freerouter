<?php


namespace FreeRouter\Attributes\Class;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class RequestPrefix
{

    private string $prefix;
    private ?string $name;

    public function __construct(string $prefix, ?string $name = null) {
        $this->prefix = $prefix;
        $this->name = $name;
    }

    public function getPrefix(): string {
        return $this->prefix;
    }

    public function getName(): ?string {
        return $this->name;
    }
}