<?php


namespace App\Shared\Domain\Model;


interface  ValueObjectInterface
{
    public function sameValueAs(ValueObjectInterface $other): bool;

    public function value();

    public function __toString(): string;
}