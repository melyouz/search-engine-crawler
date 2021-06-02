<?php


namespace App\Hits\Application;


use App\Hits\Domain\Model\Hit\SearchEngineName;

interface SearchEngineFactoryInterface
{
    public function get(SearchEngineName $searchEngine): SearchEngineInterface;
}