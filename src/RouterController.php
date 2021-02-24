<?php
declare(strict_types=1);

namespace FreeRouter;


use FreeRouter\Interface\IRouter;
use FreeRouter\Interface\IRouterController;

class RouterController
{

    public function run(IRouterController $class, RouterWrapper $callback): void {
        $array = $class->getRouters();

        /** @var IRouter $arr */
        foreach ($array as $arr) {
            $callback->router($arr);
        }
    }
}