<?php declare(strict_types=1);

namespace App\Infrastructure\Framework\Serializer;

use InvalidArgumentException;

class ChainedSerializer implements SerializerInterface
{
    /**
     * @var SerializerInterface[]|array
     */
    private $serializers;

    /**
     * @param SerializerInterface[] $serializers
     */
    public function __construct(array $serializers)
    {

        $this->serializers = $serializers;
    }

    /**
     * @inheritDoc
     */
    public function serialize($data): array
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->canSerialize($data)) {
                return $serializer->serialize($data);
            }
        }

        throw new InvalidArgumentException(sprintf('Can not find serializer for %s', get_class($data)));
    }

    /**
     * @inheritDoc
     */
    public function canSerialize($data): bool
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->canSerialize($data)) {
                return true;
            }
        }

        return false;
    }
}
