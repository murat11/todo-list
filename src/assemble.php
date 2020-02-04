<?php declare(strict_types=1);

use App\Application\Domain\EventHandlers\TodoListCreatedEventHandler;
use App\Application\Domain\EventHandlers\TodoListDeletedEventHandler;
use App\Application\UseCases\TodoListChangeItemsStatus\TodoListChangeItemsStatusCommandValidator;
use App\Application\UseCases\TodoListCreate\TodoListCreateCommandValidator;
use App\Application\UseCases\TodoListCreateItem\TodoListCreateItemCommandValidator;
use App\Domain\TodoList\Events\TodoListCreatedEvent;
use App\Domain\TodoList\Events\TodoListDeletedEvent;
use App\Domain\TodoList\TodoListManager\TodoListManager;
use App\Infrastructure\Framework\Api\RequestHandlerFactory;
use App\Infrastructure\Api\Serializer\TodoListItemSerializer;
use App\Infrastructure\Api\Serializer\TodoListSerializer;
use App\Infrastructure\Framework\CommandBus\CommandBus;
use App\Infrastructure\Framework\CommandBus\HandlerResolver;
use App\Infrastructure\Framework\EventManager\EventManager;
use App\Infrastructure\Framework\Notifications\NotificationSender;
use App\Infrastructure\Repository\TodoListDbalRepository;
use App\Infrastructure\Framework\IdGenerator\UuidGenerator;
use App\Infrastructure\Framework\Serializer\ChainedSerializer;
use App\Infrastructure\Framework\Uri\UrlBuilder;
use App\Infrastructure\Framework\Validator\ChainedValidator;
use Doctrine\DBAL\DriverManager;
use Twig\Environment as Twig;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

$projectRoot = realpath('..');
$twig = new Twig(
    new FilesystemLoader($projectRoot.'/templates/email'),
    ['cache' => false]
);

$frontendUrlBuilder = new UrlBuilder(getenv('APP_FRONTEND_URL'));
$twig->addFunction(
    new TwigFunction(
        'buildFrontendUrl',
        function (string $path, array $parameters = null) use ($frontendUrlBuilder) {
            return $frontendUrlBuilder->buildUrl($path, $parameters);
        }
    )
);

$notificationSender = new NotificationSender(
    [
        'host' => getenv('NOTIFICATION_SMTP_HOST'),
        'port' => getenv('NOTIFICATION_SMTP_PORT'),
        'username' => getenv('NOTIFICATION_SMTP_USERNAME'),
        'password' => getenv('NOTIFICATION_SMTP_PASSWORD'),
        'fromEmail' => getenv('NOTIFICATION_FROM_ADDRESS'),
    ],
    $twig
);

$eventManager = new EventManager();
$eventManager->subscribe(TodoListCreatedEvent::NAME, new TodoListCreatedEventHandler($notificationSender));
$eventManager->subscribe(TodoListDeletedEvent::NAME, new TodoListDeletedEventHandler($notificationSender));

$commandBus = new CommandBus(
    new HandlerResolver(
        new TodoListManager(
            new TodoListDbalRepository(
                DriverManager::getConnection(['url' => getenv('DB_URL')]),
                new UuidGenerator()
            ),
            $eventManager
        )
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
