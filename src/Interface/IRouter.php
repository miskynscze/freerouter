<?php


namespace FreeRouter\Interface;


interface IRouter
{

    public function before(): void;
    public function after(): void;
}