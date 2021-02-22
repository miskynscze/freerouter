<?php
declare(strict_types=1);

use FreeRouter\Attributes\Method;
use FreeRouter\Attributes\Request;
use FreeRouter\Attributes\RequestMethod;
use FreeRouter\Interface\IRouter;

require '../vendor/autoload.php';

class Index implements IRouter {

    #[Request("/{id?}")]
    #[Method(RequestMethod::GET)]
    public function home(): void {
        echo "Domů";
    }

    #[Request("/test")]
    #[Method(RequestMethod::GET)]
    public function test(): void {
        echo "test";
    }

    public function before(): void
    {
        // TODO: Implement before() method.
    }

    public function after(): void
    {
        // TODO: Implement after() method.
    }
}

$config = new \FreeRouter\RouterConfig();
$router = new \FreeRouter\RouterWrapper();
$router
    ->config($config)
    ->run(new Index());