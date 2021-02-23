<?php


namespace FreeRouter\Interface;


interface IController
{

    public function render($data): void;
}