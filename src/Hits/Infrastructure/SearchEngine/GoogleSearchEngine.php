<?php


namespace App\Hits\Infrastructure\SearchEngine;


use App\Hits\Application\SearchEngineInterface;
use App\Hits\Domain\Model\Hit\SearchTerm;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleSearchEngine implements SearchEngineInterface
{
    use HtmlFetcherTrait;
    use UrlGuesserTrait;

    private HttpClientInterface $httpClient;
    private string $url;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->httpClient = $httpClient;
        $this->url = $parameterBag->get('app.hits.google_url');
    }

    public function getDomains(SearchTerm $searchTerm): array
    {
        $links = $this->fetchLinks($this->url, ['q' => $searchTerm->value()], '#main > div > div > div:nth-child(1) > a');
        if (empty($links)) {
            return [];
        }

        return $this->guessDomains($links, 'q');
    }
}