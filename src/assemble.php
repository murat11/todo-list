<?php declare(strict_types=1);

use App\Application\Notifications\TodoListCreatedNotificationSender;
use App\Application\Notifications\TodoListDeletedNotificationSender;
use App\Application\UseCases\TodoListChangeItemsStatus\TodoListChangeItemsStatusCommandValidator;
use App\Application\UseCases\TodoListCreate\TodoListCreateCommandValidator;
use App\Application\UseCases\TodoListCreateItem\TodoListCreateItemCommandValidator;
use App\Domain\Events\TodoListCreatedEvent;
use App\Domain\Events\TodoListDeletedEvent;
use App\Infrastructure\Api\RequestHandlerFactory;
use App\Infrastructure\Api\Serializer\TodoListItemSerializer;
use App\Infrastructure\Api\Serializer\TodoListSerializer;
use App\Infrastructure\CommandBus\CommandBus;
use App\Infrastructure\CommandBus\HandlerResolver;
use App\Infrastructure\EventManager\EventManager;
use App\Infrastructure\Notifications\FileSystemNotificationSender;
use App\Infrastructure\Repository\DbalTodoListRepository;
use App\Infrastructure\Repository\IdGenerator\UuidGenerator;
use App\Infrastructure\Serializer\ChainedSerializer;
use App\Application\Validator\ChainedValidator;
use Doctrine\DBAL\DriverManager;

$notificationSender = new FileSystemNotificationSender(realpath('..') . '/var/notifications.txt');

$eventManager = new EventManager();
$eventManager->subscribe(TodoListCreatedEvent::NAME, new TodoListCreatedNotificationSender($notificationSender));
$eventManager->subscribe(TodoListDeletedEvent::NAME, new TodoListDeletedNotificationSender($notificationSender));

$commandBus = new CommandBus(
    new HandlerResolver(
        new DbalTodoListRepository(
            DriverManager::getConnection(['url' => 'mysql://root@mysql-dev:3306/app_dev']),
            new UuidGenerator()
        ),
        $eventManager
    ),
    new ChainedValidator(
        [
            new TodoListCreateCommandValidator(),
            new TodoListCreateItemCommandValidator(),
            new TodoListChangeItemsStatusCommandValidator(),
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
