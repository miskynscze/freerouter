<?php
declare(strict_types=1);

namespace FreeRouter;

use FreeRouter\Attributes\Class\RequestPrefix;
use FreeRouter\Interface\IRouter;
use FreeRouter\Interface\IRouterController;
use FreeRouter\Tools\ClassRunner;
use FreeRouter\Tools\ServerRequest;

class RouterWrapper
{

    private RouterConfig $config;

    public function config(RouterConfig $config): RouterWrapper {
        $this->config = $config;
        return $this;
    }

    public function run(IRouter|IRouterController $class): void {
        if(!isset($this->config)) {
            echo "Config was not set, creating default";
            $this->config = RouterConfig::getConfig();
        }

        if($class instanceof IRouterController) {
            $rController = new RouterController();
            $rController->run($class, $this);
        } elseif($class instanceof IRouter) {
            $this->router($class);
        }
    }

    public function router(IRouter $class): void {
        $reflection = new \ReflectionClass($class::class);
        $prefix = $this->getClassPrefix($reflection);
        $lastFunction = null;
        $method = null;
        $request = null;
        $found = false;

        foreach ($reflection->getMethods() as $method) {
            if($found) {
                continue;
            }

            $lastFunction = $method->getName();

            if(in_array($lastFunction, ["before", "after"])) {
                continue;
            }

            foreach ($method->getAttributes() as $attribute) {
                if(str_contains($attribute->getName(), "Request")) {
                    $request = $prefix . $attribute->getArguments()[0];
                } elseif(str_contains($attribute->getName(), "Method")) {
                    $method = $attribute->getArguments()[0];
                }
            }

            $found = ServerRequest::request($request, $method);
        }

        if($found) {
            $classRunner = new ClassRunner();
            $classRunner
                ->setClass($class)
                ->setAttributes(ServerRequest::getTemporaryData()["attributes"])
                ->setPathTemplate($request)
                ->runFunction($lastFunction);
        }
    }

    private function getClassPrefix(\ReflectionClass $class): string {
        $attributes = $class->getAttributes();

        foreach ($attributes as $attribute) {
            if(str_contains($attribute->getName(), "RequestPrefix")) {
                /** @var RequestPrefix $instance */
                $instance = $attribute->newInstance();

                return $instance->getPrefix();
            }
        }

        return "";
    }
}