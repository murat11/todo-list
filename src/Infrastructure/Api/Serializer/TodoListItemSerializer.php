<?php declare(strict_types=1);

namespace App\Infrastructure\Api\Serializer;

use App\Domain\TodoList\TodoListItem;
use App\Infrastructure\Serializer\SerializerInterface;

class TodoListItemSerializer implements SerializerInterface
{
    /**
     * @param TodoListItem $data
     *
     * @return array
     */
    public function serialize($data): array
    {
        return [
            'id' => $data->getId(),
            'title' => $data->getTitle(),
            'completed' => $data->isCompleted(),
        ];
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function canSerialize($data): bool
    {
        return $data instanceof TodoListItem;
    }
}
