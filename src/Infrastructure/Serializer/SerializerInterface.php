<?php declare(strict_types=1);

namespace App\Infrastructure\Serializer;

interface SerializerInterface
{
    /**
     * @param $data
     *
     * @return array
     */
    public function serialize($data): array;

    /**
     * @param $data
     *
     * @return bool
     */
    public function canSerialize($data): bool;
}
