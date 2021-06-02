<?php


namespace App\Hits\Domain\Model\Hit;

use App\Shared\Domain\Model\Uuid;

class HitId extends Uuid
{
    public static function fromString(string $value): self
    {
        return new self($value);
    }
}