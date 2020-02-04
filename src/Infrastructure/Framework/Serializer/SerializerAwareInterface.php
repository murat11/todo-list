<?php declare(strict_types=1);

namespace App\Infrastructure\Framework\Serializer;

interface SerializerAwareInterface
{
    public function setSerializer(SerializerInterface $serializer);
}
