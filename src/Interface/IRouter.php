<?php
declare(strict_types=1);

namespace FreeRouter\Interface;


interface IRouter
{

    public function before(): void;
    public function after(): void;
}