<?php


namespace App\Hits\Infrastructure\SearchEngine;


use App\Hits\Domain\Model\Hit\Domain;

trait UrlGuesserTrait
{
    private function guessDestUrlHostByUrl(string $destUrl): ?string
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

    private function guessDestUrlHostBySelfPath(string $destPath, string $paramName): ?string
    {
        $urlComponents = parse_url($destPath);

        if (!isset($urlComponents['query'])) {
            return null;
        }

        $parsedQueryParams = [];
        parse_str($urlComponents['query'], $parsedQueryParams);

        if (!isset($parsedQueryParams[$paramName])) {
            return null;
        }

        $destUrl = $parsedQueryParams[$paramName];
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

    private function guessDomains(array $links, ?string $pathParamName = null): array
    {
        $domains = [];
        foreach ($links as $item) {
            $href = $item->attributes->getNamedItem('href')->nodeValue;
            $destUrlHost = ($pathParamName ? $this->guessDestUrlHostBySelfPath($href, $pathParamName) : $this->guessDestUrlHostByUrl($href));

            if (!$destUrlHost) {
                continue;
            }

            $domains[] = Domain::fromString($destUrlHost);
        }

        return $domains;
    }
}