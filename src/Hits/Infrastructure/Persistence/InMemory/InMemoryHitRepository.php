<?php


namespace App\Hits\Infrastructure\Persistence\InMemory;


use App\Hits\Domain\Dto\DomainHitCount;
use App\Hits\Domain\Model\Hit;
use App\Hits\Domain\Model\Hit\Domain;
use App\Hits\Domain\Model\Hit\HitId;
use App\Hits\Domain\Model\Hit\SearchEngineName;
use App\Hits\Domain\Repository\HitRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class InMemoryHitRepository implements HitRepositoryInterface
{
    /** @var Hit[] */
    private array $hits = [];

    public function allGroupedByDomain(SearchEngineName $searchEngineName): array
    {
        $domainsCounts = [];
        foreach ($this->hits as $hit) {

            if (!$hit->getSearchEngine()->sameValueAs($searchEngineName)) {
                continue;
            }

            $key = $hit->getDomain()->value();
            if (!array_key_exists($key, $domainsCounts)) {
                $domainsCounts[$key] = 0;
            }

            $domainsCounts[$key]++;
        }

        $result = [];
        foreach ($domainsCounts as $domain => $count) {
            $result[] = new DomainHitCount(Domain::fromString($domain), $count);
        }

        return $result;
    }

    public function save(Hit $hit): void
    {
        $key = $hit->getId()->value();

        if (!array_key_exists($key, $this->hits)) {
            $this->hits[$key] = $hit;
        }
    }

    public function nextIdentity(): HitId
    {
        return HitId::fromString((string)Uuid::v4());
    }
}