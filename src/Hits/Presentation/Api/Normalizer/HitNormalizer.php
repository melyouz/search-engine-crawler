<?php


namespace App\Hits\Presentation\Api\Normalizer;


use App\Hits\Domain\Dto\DomainHitCount;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class HitNormalizer implements NormalizerInterface
{
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'domain' => $object->getDomain(),
            'count' => $object->getCount(),
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof DomainHitCount;
    }
}