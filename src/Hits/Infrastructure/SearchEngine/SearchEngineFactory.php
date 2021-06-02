<?php


namespace App\Hits\Infrastructure\SearchEngine;


use App\Hits\Application\SearchEngineFactoryInterface;
use App\Hits\Application\SearchEngineInterface;
use App\Hits\Domain\Model\Hit\SearchEngineName;
use InvalidArgumentException;

class SearchEngineFactory implements SearchEngineFactoryInterface
{
    private GoogleSearchEngine $googleSearchEngine;
    private BingSearchEngine $bingSearchEngine;

    public function __construct(GoogleSearchEngine $googleSearchEngine, BingSearchEngine $bingSearchEngine)
    {
        $this->googleSearchEngine = $googleSearchEngine;
        $this->bingSearchEngine = $bingSearchEngine;
    }

    public function get(SearchEngineName $searchEngine): SearchEngineInterface
    {
        switch ($searchEngine->value()) {
            case SearchEngineName::BING:
                return $this->bingSearchEngine;
            case SearchEngineName::GOOGLE:
                return $this->googleSearchEngine;
            default:
                throw new InvalidArgumentException(sprintf('Unhandled search engine "%s"', $searchEngine));
        }
    }
}