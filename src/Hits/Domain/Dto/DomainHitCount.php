<?php


namespace App\Hits\Domain\Dto;

use App\Hits\Domain\Model\Hit\Domain;
use InvalidArgumentException;

class DomainHitCount
{
    private const MIN_COUNT = 1;

    private Domain $domain;
    private int $count;

    public function __construct(Domain $domain, int $count)
    {
        $this->countValidationGuard($count);

        $this->domain = $domain;
        $this->count = $count;
    }

    public static function fromArray(array $data): self
    {
        return new self(Domain::fromString($data['domain']), $data['count']);
    }

    private function countValidationGuard(int $count): void
    {
        if ($count < self::MIN_COUNT) {
            throw new InvalidArgumentException(sprintf('Count must be greater or equal to %d.', self::MIN_COUNT));
        }
    }

    public function getDomain(): Domain
    {
        return $this->domain;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}