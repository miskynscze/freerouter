<?php
declare(strict_types=1);

namespace FreeRouter\Attributes\Class;

use Attribute;
use FreeRouter\Interface\IController;

#[Attribute(Attribute::TARGET_CLASS)]
class Controller implements IController
{
    public function render($data): void
    {
        echo $data;
    }
}