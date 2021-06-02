<?php


namespace App\Shared\Domain\Model;


use Assert\Assertion;

abstract class Uuid extends AbstractStringValueObject
{
    public function __construct(string $value)
    {
        Assertion::notBlank($value);
        Assertion::uuid($value);

        parent::__construct($value);
    }
}