<?php


namespace App\Hits\Infrastructure\Persistence\Doctrine;

use App\Hits\Domain\Dto\DomainHitCount;
use App\Hits\Domain\Model\Hit;
use App\Hits\Domain\Model\Hit\HitId;
use App\Hits\Domain\Model\Hit\SearchEngineName;
use App\Hits\Domain\Repository\HitRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Uuid;
use function Doctrine\ORM\QueryBuilder;

class DoctrineHitRepository implements HitRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Hit::class);
    }

    public function allGroupedByDomain(SearchEngineName $searchEngineName): array
    {
        $queryBuilder = $this->repository->createQueryBuilder('h');
        $queryBuilder
            ->select('h.domain, COUNT(h.domain) AS count')
            ->where($queryBuilder->expr()->eq('h.searchEngine', ':searchEngineName'))
            ->groupBy('h.domain')
            ->orderBy('count', 'DESC')
            ->addOrderBy('h.domain', 'ASC');
        $queryBuilder->setParameters(['searchEngineName' => $searchEngineName->value()]);
        $query = $queryBuilder->getQuery();
        $result = $query->getScalarResult();

        if (empty($result)) {
            return [];
        }

        return array_map(function ($item) {
            return DomainHitCount::fromArray($item);
        }, $result);
    }

    public function save(Hit $hit): void
    {
        $this->em->persist($hit);
        $this->em->flush();
    }

    public function nextIdentity(): HitId
    {
        return HitId::fromString((string)Uuid::v4());
    }
}