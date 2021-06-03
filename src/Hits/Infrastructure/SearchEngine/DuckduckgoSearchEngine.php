<?php


namespace App\Hits\Infrastructure\SearchEngine;


use App\Hits\Application\SearchEngineInterface;
use App\Hits\Domain\Model\Hit\SearchTerm;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DuckduckgoSearchEngine implements SearchEngineInterface
{
    use HtmlFetcherTrait;
    use UrlGuesserTrait;

    private HttpClientInterface $httpClient;
    private string $url;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->httpClient = $httpClient;
        $this->url = $parameterBag->get('app.hits.duckduckgo_url');
    }

    public function getDomains(SearchTerm $searchTerm): array
    {
        $links = $this->fetchLinks($this->url, ['q' => $searchTerm->value()], '#links a.result__url');
        if (empty($links)) {
            return [];
        }

        return $this->guessDomains($links, 'uddg');
    }
}