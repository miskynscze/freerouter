<?php
declare(strict_types=1);

namespace FreeRouter;

use FreeRouter\Attributes\Class\RequestPrefix;
use FreeRouter\Attributes\Method;
use FreeRouter\Attributes\Request;
use FreeRouter\Attributes\RequestMethod;
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
        $requestMethod = null;
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

            //Using prefix (get, post, put...) as a prefix of a function
            if($this->config->isUsingMethodAsPrefix()) {
                foreach (RequestMethod::REQUEST_HELPER as $key => $value) {
                    if($requestMethod === null) {
                        continue;
                    }

                    if(str_starts_with($lastFunction, $value)) {
                        $requestMethod = $key;
                    }
                }
            }

            foreach ($method->getAttributes() as $attribute) {
                $instance = $attribute->newInstance();
                if($instance instanceof Request) {
                    $request = $prefix . $instance->getPath();
                } elseif($requestMethod === null && $instance instanceof Method) {
                    $requestMethod = $instance->getMethod();
                }
            }

            //Using default value method if value is not defined
            //TODO: In future should throw error
            if($requestMethod === null) {
                $requestMethod = $this->config->getDefaultMethod();
            }

            $found = ServerRequest::request($request, $requestMethod);
        }

        if($found) {
            $classRunner = new ClassRunner($this->config);
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
            $instance = $attribute->newInstance();
            if($instance instanceof RequestPrefix) {
                return $instance->getPrefix();
            }
        }

        return "";
    }
}