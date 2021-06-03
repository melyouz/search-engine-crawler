<?php


namespace Hits\Application\Service;


use App\Hits\Application\Service\ListService;
use App\Hits\Domain\Dto\DomainHitCount;
use App\Hits\Domain\Model\Hit;
use App\Hits\Domain\Model\Hit\Domain;
use App\Hits\Domain\Model\Hit\SearchEngineName;
use App\Hits\Domain\Model\Hit\SearchTerm;
use App\Hits\Infrastructure\Persistence\InMemory\InMemoryHitRepository;
use PHPUnit\Framework\TestCase;

class ListServiceTest extends TestCase
{
    private ListService $listService;

    public function setUp(): void
    {
        $repository = new InMemoryHitRepository();
        $this->listService = new ListService($repository);

        $hits = [
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::GOOGLE), SearchTerm::fromString('test'), Domain::fromString('es.linkedin.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::GOOGLE), SearchTerm::fromString('test'), Domain::fromString('www.microsoft.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::GOOGLE), SearchTerm::fromString('test'), Domain::fromString('es.linkedin.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::GOOGLE), SearchTerm::fromString('test2'), Domain::fromString('nemontradeenergy.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::GOOGLE), SearchTerm::fromString('test2'), Domain::fromString('es.linkedin.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::BING), SearchTerm::fromString('test'), Domain::fromString('es.linkedin.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::BING), SearchTerm::fromString('test'), Domain::fromString('www.microsoft.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::BING), SearchTerm::fromString('test'), Domain::fromString('es.linkedin.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::BING), SearchTerm::fromString('test2'), Domain::fromString('nemontradeenergy.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::BING), SearchTerm::fromString('test2'), Domain::fromString('es.linkedin.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::BING), SearchTerm::fromString('test2'), Domain::fromString('test-domain.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::DUCKDUCKGO), SearchTerm::fromString('nemon'), Domain::fromString('es.linkedin.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::DUCKDUCKGO), SearchTerm::fromString('nemon'), Domain::fromString('nemontradeenergy.com')),
            new Hit($repository->nextIdentity(), SearchEngineName::fromString(SearchEngineName::DUCKDUCKGO), SearchTerm::fromString('linkedin'), Domain::fromString('es.linkedin.com')),
        ];

        foreach($hits as $hit) {
            $repository->save($hit);
        }
    }

    public function testGoogleDomainsList(): void
    {
        $googleHitsDomains = $this->listService->list(SearchEngineName::GOOGLE);

        $this->assertIsArray($googleHitsDomains);
        $this->assertContainsOnlyInstancesOf(DomainHitCount::class, $googleHitsDomains);
        $this->assertCount(3, $googleHitsDomains);

        $this->assertEquals('es.linkedin.com', $googleHitsDomains[0]->getDomain()->value());
        $this->assertEquals(3, $googleHitsDomains[0]->getCount());
    }

    public function testBingDomainsList(): void
    {
        $bingHitsDomains = $this->listService->list(SearchEngineName::BING);

        $this->assertIsArray($bingHitsDomains);
        $this->assertContainsOnlyInstancesOf(DomainHitCount::class, $bingHitsDomains);
        $this->assertCount(4, $bingHitsDomains);

        $this->assertEquals('es.linkedin.com', $bingHitsDomains[0]->getDomain()->value());
        $this->assertEquals(3, $bingHitsDomains[0]->getCount());
    }

    public function testDuckduckgoDomainsList(): void
    {
        $ddgHitsDomains = $this->listService->list(SearchEngineName::DUCKDUCKGO);

        $this->assertIsArray($ddgHitsDomains);
        $this->assertContainsOnlyInstancesOf(DomainHitCount::class, $ddgHitsDomains);
        $this->assertCount(2, $ddgHitsDomains);

        $this->assertEquals('es.linkedin.com', $ddgHitsDomains[0]->getDomain()->value());
        $this->assertEquals(2, $ddgHitsDomains[0]->getCount());

        $this->assertEquals('nemontradeenergy.com', $ddgHitsDomains[1]->getDomain()->value());
        $this->assertEquals(1, $ddgHitsDomains[1]->getCount());
    }
}