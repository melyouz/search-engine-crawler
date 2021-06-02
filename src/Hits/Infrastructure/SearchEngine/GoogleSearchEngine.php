<?php


namespace App\Hits\Infrastructure\SearchEngine;


use App\Hits\Application\SearchEngineInterface;
use App\Hits\Domain\Model\Hit\Domain;
use App\Hits\Domain\Model\Hit\SearchTerm;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleSearchEngine implements SearchEngineInterface
{
    private HttpClientInterface $httpClient;
    private string $url;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->httpClient = $httpClient;
        $this->url = $parameterBag->get('app.hits.google_url');
    }

    public function getDomains(SearchTerm $searchTerm): array
    {
        $domains = [];

        if (!$html = $this->fetchHtml($searchTerm)) {
            return [];
        }

        $crawler = new Crawler($html);
        $links = $crawler->filter('#main > div > div > div:nth-child(1) > a');

        if (!$links->count()) {
            return [];
        }

        foreach ($links as $link) {
            $googleDestPath = $link->attributes->getNamedItem('href')->nodeValue;

            if (!$destUrlHost = $this->guessDestUrlHost($googleDestPath)) {
                continue;
            }

            $domains[] = Domain::fromString($destUrlHost);
        }

        return $domains;
    }

    private function fetchHtml(SearchTerm $searchTerm): ?string
    {
        $response = $this->httpClient->request('GET', $this->url, [
            'query' => ['q' => $searchTerm->value()],
        ]);

        if ($response->getStatusCode() != 200) {
            return null;
        }

        return $response->getContent();
    }

    private function guessDestUrlHost(string $googleDestPath): ?string
    {
        $urlComponents = parse_url($googleDestPath);

        if (!isset($urlComponents['query'])) {
            return null;
        }

        $parsedQueryParams = [];
        parse_str($urlComponents['query'], $parsedQueryParams);

        if (!isset($parsedQueryParams['q'])) {
            return null;
        }

        $destUrl = $parsedQueryParams['q'];
        $destUrlIsValid = (bool)filter_var($destUrl, FILTER_VALIDATE_URL);

        if (!$destUrlIsValid) {
            return null;
        }

        $urlComponents = parse_url($destUrl);

        if (!isset($urlComponents['host'])) {
            return null;
        }

        return $urlComponents['host'];
    }
}