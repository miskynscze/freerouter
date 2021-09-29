<?php
declare(strict_types=1);

namespace FreeRouter\Attributes;


class RequestMethod
{

    public const GET = 1;
    public const POST = 2;
    public const DELETE = 3;
    public const PUT = 4;

    public const REQUEST_HELPER = [
        self::GET => "get",
        self::POST => "post",
        self::DELETE => "delete",
        self::PUT => "put"
    ];
}