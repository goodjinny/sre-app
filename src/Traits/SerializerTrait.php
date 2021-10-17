<?php

declare(strict_types=1);

namespace App\Traits;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * SerializerTrait.
 */
trait SerializerTrait
{
    /** @var SerializerInterface|Serializer */
    protected SerializerInterface $serializer;

    /**
     * @param SerializerInterface|Serializer $serializer
     *
     * @required
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        if (!$this->serializer instanceof Serializer) {
            throw new \RuntimeException(sprintf('Serializer is not instance of %s', Serializer::class));
        }

        return $this->serializer;
    }
}
