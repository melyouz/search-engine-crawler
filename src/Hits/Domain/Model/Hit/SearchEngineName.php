<?php


namespace App\Hits\Domain\Model\Hit;


use App\Shared\Domain\Model\AbstractStringValueObject;
use Assert\Assertion;

class SearchEngineName extends AbstractStringValueObject
{
    public const GOOGLE = 'google';
    public const BING = 'bing';
    public const DUCKDUCKGO = 'duckduckgo';

    public const CHOICES = [
        self::GOOGLE,
        self::BING,
        self::DUCKDUCKGO,
    ];

    public static function fromString(string $value): self
    {
        Assertion::notBlank($value);
        Assertion::maxLength($value, self::MAX_LENGTH);
        Assertion::inArray($value, self::CHOICES);

        return new self($value);
    }
}