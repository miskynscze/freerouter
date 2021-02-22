<?php
declare(strict_types=1);

namespace FreeRouter\Tools;


class ServerRequest
{

    public static function request(string $path, int $method): bool {
        $request = self::getRequestUri();

        return $path === $request && self::isMethod($method);
    }

    public static function isMethod(int $method): bool {
        $reqMethod = $_SERVER["REQUEST_METHOD"];
        $reqMethod = constant("FreeRouter\Attributes\RequestMethod::$reqMethod");

        return $reqMethod === $method;
    }

    public static function getRequestUri() {
        $exploded = explode("/", $_SERVER["REQUEST_URI"]);
        array_shift($exploded);

        return "/" . $exploded[0];
    }
}