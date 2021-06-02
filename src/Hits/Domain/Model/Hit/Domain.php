<?php


namespace App\Hits\Domain\Model\Hit;


use App\Shared\Domain\Model\AbstractStringValueObject;
use Assert\Assertion;

class Domain extends AbstractStringValueObject
{
    public static function fromString(string $value): self
    {
        Assertion::notBlank($value);
        Assertion::maxLength($value, self::MAX_LENGTH);

        return new self($value);
    }
}