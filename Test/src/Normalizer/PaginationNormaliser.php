<?php

namespace App\Normaliser;

use App\Entity\Ride;
use ArrayObject;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Normalizer;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormaliser implements NormalizerInterface {

    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')] 
        private readonly NormalizerInterface $normalizer)
    {
        
        
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|ArrayObject|null
    {
        if (!($object instanceof PaginationInterface)) {
            throw new \RuntimeException();
        }

        return [
            'items' => array_map( fn (Ride $ride) => $this->normalizer->normalize($ride, $format, $context), $object->getItems()),
            'total' => $object->getTotalItemCount(),
            'page' => $object->getCurrentPageNumber(),
            'lastPage' => ceil($object->getTotalItemCount() / $object->getItemNumberPerPage())
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof PaginationInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginatorInterface::class => true
        ];
        
    }
}