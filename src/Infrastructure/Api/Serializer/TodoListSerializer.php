<?php declare(strict_types=1);

namespace App\Infrastructure\Api\Serializer;

use App\Domain\TodoList\TodoList;
use App\Infrastructure\Framework\Serializer\SerializerInterface;

class TodoListSerializer implements SerializerInterface
{
    /**
     * @var TodoListItemSerializer
     */
    private $todoListItemSerializer;

    /**
     * @param TodoListItemSerializer $todoListItemSerializer
     */
    public function __construct(TodoListItemSerializer $todoListItemSerializer)
    {
        $this->todoListItemSerializer = $todoListItemSerializer;
    }

    /**
     * @param TodoList $data
     *
     * @return array
     */
    public function serialize($data): array
    {
        $output = [
            'id' => $data->getId(),
            'name' => $data->getName(),
            'participants' => $data->getParticipantEmails(),
            'todos' => array_map([$this->todoListItemSerializer, 'serialize'], $data->getItems()),
        ];

        return $output;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function canSerialize($data): bool
    {
        return $data instanceof TodoList;
    }
}
