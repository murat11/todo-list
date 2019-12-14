<?php declare(strict_types=1);

namespace App\Infrastructure\Serializer;

interface SerializerAwareInterface
{
    public function setSerializer(SerializerInterface $serializer);
}
