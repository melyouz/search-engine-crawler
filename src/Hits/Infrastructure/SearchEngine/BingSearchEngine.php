<?php


namespace App\Hits\Infrastructure\SearchEngine;


use App\Hits\Application\SearchEngineInterface;
use App\Hits\Domain\Model\Hit\Domain;
use App\Hits\Domain\Model\Hit\SearchTerm;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BingSearchEngine implements SearchEngineInterface
{
    private HttpClientInterface $httpClient;
    private string $url;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->httpClient = $httpClient;
        $this->url = $parameterBag->get('app.hits.bing_url');
    }

    public function getDomains(SearchTerm $searchTerm): array
    {
        $domains = [];
        if (!$html = $this->fetchHtml($searchTerm)) {
            return [];
        }

        $crawler = new Crawler($html);

        foreach ($crawler->filter('#b_results > li > h2 > a') as $item) {
            $destUrl = $item->attributes->getNamedItem('href')->nodeValue;
            if (!$destUrlHost = $this->guessDestUrlHost($destUrl)) {
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

    private function guessDestUrlHost(string $destUrl): ?string
    {
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