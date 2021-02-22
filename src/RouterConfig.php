<?php
declare(strict_types=1);

namespace FreeRouter;


use FreeRouter\Attributes\RequestMethod;
use JetBrains\PhpStorm\Pure;

class RouterConfig
{

    protected int $DEFAULT_METHOD   = RequestMethod::GET;
    protected bool $BEAF_FUNCTIONS  = true;
    protected bool $CACHE_ROUTE     = false;

    #[Pure]
    public static function getConfig(): RouterConfig {
        return new self();
    }

    public function allowBeAfFunctions(bool $allow): RouterConfig {
        $this->BEAF_FUNCTIONS = $allow;

        return $this;
    }

    public function isAllowedBeAfFunctions(): bool {
        return $this->BEAF_FUNCTIONS;
    }

    public function setDefaultMethod(int $defaultMethod): RouterConfig {
        $this->DEFAULT_METHOD = $defaultMethod;

        return $this;
    }

    public function getDefaultMethod(): int {
        return $this->DEFAULT_METHOD;
    }

    public function setCacheRoute(bool $cache): RouterConfig {
        $this->CACHE_ROUTE = $cache;

        return $this;
    }

    public function isCacheRoute(): bool {
        return $this->CACHE_ROUTE;
    }
}