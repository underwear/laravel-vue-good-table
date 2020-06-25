<?php


namespace LaravelVueGoodTable\Contracts;


interface JsonSerializable
{
    public function jsonSerialize(): array;
}