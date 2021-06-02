<?php


namespace App\Hits\Application\Service;


use App\Hits\Application\SearchEngineFactoryInterface;
use App\Hits\Domain\Model\Hit;
use App\Hits\Domain\Model\Hit\HitId;
use App\Hits\Domain\Model\Hit\SearchEngineName;
use App\Hits\Domain\Model\Hit\SearchTerm;
use App\Hits\Domain\Repository\HitRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class SearchService
{
    private SearchEngineFactoryInterface $searchEngineFactory;
    private HitRepositoryInterface $hitRepository;

    public function __construct(SearchEngineFactoryInterface $searchEngineFactory, HitRepositoryInterface $hitRepository)
    {
        $this->searchEngineFactory = $searchEngineFactory;
        $this->hitRepository = $hitRepository;
    }

    public function search(string $searchEngineName, string $searchTerm): void
    {
        $searchEngineName = SearchEngineName::fromString($searchEngineName);
        $searchTerm = SearchTerm::fromString($searchTerm);

        $searchEngine = $this->searchEngineFactory->get($searchEngineName);
        $domains = $searchEngine->getDomains($searchTerm);

        foreach ($domains as $domain) {
            $hitId = HitId::fromString((string)Uuid::v4());
            $hit = new Hit($hitId, $searchEngineName, $searchTerm, $domain);
            $this->hitRepository->save($hit);
        }
    }
}