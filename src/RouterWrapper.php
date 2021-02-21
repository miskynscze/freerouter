<?php
declare(strict_types=1);

namespace FreeRouter;

use FreeRouter\Interface\IRouter;

class RouterWrapper
{

    public function config(): RouterWrapper {
        return $this;
    }

    public function run(IRouter $class): void {
        $reflection = new \ReflectionClass($class::class);
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
                    $request = $attribute->getArguments()[0];
                } elseif(str_contains($attribute->getName(), "Method")) {
                    $method = $attribute->getArguments()[0];
                }
            }

            $found = $this->request($request, $method);
        }

        if($found) {
            $class->before();
            $class->{$lastFunction}();
            $class->after();
        }
    }

    private function request($path, $method): bool {
        $request = $this->getRequestUri();

        if($path === $request && $this->isMethod($method)) {
            return true;
        }

        return false;
    }

    private function isMethod(int $method): bool {
        $reqMethod = $_SERVER["REQUEST_METHOD"];
        $reqMethod = constant("FreeRouter\Attributes\RequestMethod::$reqMethod");

        return ($reqMethod === $method);
    }

    private function getRequestUri() {
        $exploded = explode("/", $_SERVER["REQUEST_URI"]);
        array_shift($exploded);

        return "/" . $exploded[0];
    }
}