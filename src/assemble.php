<?php declare(strict_types=1);

use App\Application\UseCases\TodoListCreateCommandValidator;
use App\Infrastructure\Api\RequestHandlerFactory;
use App\Infrastructure\Api\Serializer\TodoListItemSerializer;
use App\Infrastructure\Api\Serializer\TodoListSerializer;
use App\Infrastructure\CommandBus\CommandBus;
use App\Infrastructure\CommandBus\HandlerResolver;
use App\Infrastructure\Repository\DbalTodoListRepository;
use App\Infrastructure\Repository\IdGenerator\UuidGenerator;
use App\Infrastructure\Serializer\ChainedSerializer;
use App\Infrastructure\Validator\ChainedValidator;
use Doctrine\DBAL\DriverManager;

$commandBus = new CommandBus(
    new HandlerResolver(
        new DbalTodoListRepository(
            DriverManager::getConnection(['url' => 'mysql://root@mysql-dev:3306/app_dev']),
            new UuidGenerator()
        )
    ),
    new ChainedValidator(
        [
            new TodoListCreateCommandValidator()
        ]
    )
);

$todoListItemSerializer = new TodoListItemSerializer();
$serializer = new ChainedSerializer(
    [
        $todoListItemSerializer,
        new TodoListSerializer($todoListItemSerializer)
    ]
);

$requestHandlerFactory = new RequestHandlerFactory($commandBus, $serializer);
