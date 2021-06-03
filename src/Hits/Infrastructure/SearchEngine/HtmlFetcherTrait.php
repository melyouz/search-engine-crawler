<?php


namespace App\Hits\Infrastructure\SearchEngine;

use Symfony\Component\DomCrawler\Crawler;

trait HtmlFetcherTrait
{
    private function fetchHtml(string $url, array $params): ?Crawler
    {
        $response = $this->httpClient->request('GET', $url, [
            'query' => $params,
        ]);

        if ($response->getStatusCode() != 200) {
            return null;
        }

        $html = $response->getContent();

        return new Crawler($html);
    }

    private function fetchLinks(string $url, array $params, string $selector): array
    {
        if (!$crawler =  $this->fetchHtml($url, $params)) {
            return [];
        }

        $links = $crawler->filter($selector);

        if (!$links->count()) {
            return [];
        }

        return $links->getIterator()->getArrayCopy();
    }
}