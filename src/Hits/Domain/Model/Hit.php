<?php


namespace App\Hits\Domain\Model;

use App\Hits\Domain\Model\Hit\Domain;
use App\Hits\Domain\Model\Hit\HitId;
use App\Hits\Domain\Model\Hit\SearchEngineName;
use App\Hits\Domain\Model\Hit\SearchTerm;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class Hit
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $searchEngine;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $searchedTerm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $domain;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(HitId $id, SearchEngineName $searchEngine, SearchTerm $searchedTerm, Domain $domain)
    {
        $this->id = $id;
        $this->searchEngine = $searchEngine;
        $this->searchedTerm = $searchedTerm;
        $this->domain = $domain;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): HitId
    {
        return HitId::fromString($this->id);
    }

    public function getSearchEngine(): SearchEngineName
    {
        return SearchEngineName::fromString($this->searchEngine);
    }

    public function getSearchedTerm(): SearchTerm
    {
        return SearchTerm::fromString($this->searchedTerm);
    }

    public function getDomain(): Domain
    {
        return Domain::fromString($this->domain);
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}