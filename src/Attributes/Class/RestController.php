<?php
declare(strict_types=1);

namespace FreeRouter\Attributes;

use Attribute;
use FreeRouter\Interface\IController;

#[Attribute(Attribute::TARGET_CLASS)]
class RestController implements IController
{

    public function render($data): void
    {
        echo json_encode($data);
    }
}