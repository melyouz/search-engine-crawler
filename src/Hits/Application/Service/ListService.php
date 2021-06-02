<?php


namespace App\Hits\Application\Service;


use App\Hits\Domain\Dto\DomainHitCount;
use App\Hits\Domain\Model\Hit\SearchEngineName;
use App\Hits\Domain\Repository\HitRepositoryInterface;

class ListService
{
    private HitRepositoryInterface $hitRepository;

    public function __construct(HitRepositoryInterface $hitRepository)
    {
        $this->hitRepository = $hitRepository;
    }

    /** @return DomainHitCount[] */
    public function list(string $searchEngineName): array
    {
        $searchEngineName = SearchEngineName::fromString($searchEngineName);

        return $this->hitRepository->allGroupedByDomain($searchEngineName);
    }
}