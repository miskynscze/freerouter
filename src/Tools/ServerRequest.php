<?php
declare(strict_types=1);

namespace FreeRouter\Tools;


class ServerRequest
{

    private static array $temporaryData;

    public static function request(string $path, int $method): bool {
        if(str_contains($path, "{")) {
            $firstPosRemove = strpos($path, "/{");
            $toRemove = substr($path, $firstPosRemove, strlen($path));

            $path = str_replace($toRemove, "", $path);
        }

        $request = self::getRequestUri();

        self::$temporaryData = $request;

        return $path === $request["request"] && self::isMethod($method);
    }

    public static function isMethod(int $method): bool {
        $reqMethod = $_SERVER["REQUEST_METHOD"];
        $reqMethod = constant("FreeRouter\Attributes\RequestMethod::$reqMethod");

        return $reqMethod === $method;
    }

    public static function getRequestUri() {
        $exploded = explode("/", $_SERVER["REQUEST_URI"]);
        array_shift($exploded);

        $request = "/" . $exploded[0];
        array_shift($exploded);

        return [
            "request" => $request,
            "attributes" => $exploded
        ];
    }

    public static function getTemporaryData(): array {
        return self::$temporaryData;
    }
}