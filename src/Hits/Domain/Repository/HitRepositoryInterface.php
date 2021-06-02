<?php


namespace App\Hits\Domain\Repository;

use App\Hits\Domain\Dto\DomainHitCount;
use App\Hits\Domain\Model\Hit;
use App\Hits\Domain\Model\Hit\HitId;
use App\Hits\Domain\Model\Hit\SearchEngineName;

interface HitRepositoryInterface
{
    /** @return DomainHitCount[] */
    public function allGroupedByDomain(SearchEngineName $searchEngineName): array;

    public function save(Hit $hit): void;

    public function nextIdentity(): HitId;
}