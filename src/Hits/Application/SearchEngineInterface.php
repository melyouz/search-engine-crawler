<?php


namespace App\Hits\Application;


use App\Hits\Domain\Model\Hit\Domain;
use App\Hits\Domain\Model\Hit\SearchTerm;

interface SearchEngineInterface
{
    /** @return Domain[] */
    public function getDomains(SearchTerm $searchTerm): array;
}