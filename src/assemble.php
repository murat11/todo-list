<?php declare(strict_types=1);

use App\Application\Notifications\TodoListCreatedNotificationSender;
use App\Application\Notifications\TodoListDeletedNotificationSender;
use App\Application\UseCases\TodoListChangeItemsStatus\TodoListChangeItemsStatusCommandValidator;
use App\Application\UseCases\TodoListCreate\TodoListCreateCommandValidator;
use App\Application\UseCases\TodoListCreateItem\TodoListCreateItemCommandValidator;
use App\Domain\TodoList\Events\TodoListCreatedEvent;
use App\Domain\TodoList\Events\TodoListDeletedEvent;
use App\Domain\TodoList\TodoListManager\TodoListManager;
use App\Infrastructure\Api\RequestHandlerFactory;
use App\Infrastructure\Api\Serializer\TodoListItemSerializer;
use App\Infrastructure\Api\Serializer\TodoListSerializer;
use App\Infrastructure\CommandBus\CommandBus;
use App\Infrastructure\CommandBus\HandlerResolver;
use App\Infrastructure\EventManager\EventManager;
use App\Infrastructure\Notifications\NotificationSender;
use App\Infrastructure\Repository\TodoListDbalRepository;
use App\Infrastructure\Repository\IdGenerator\UuidGenerator;
use App\Infrastructure\Serializer\ChainedSerializer;
use App\Infrastructure\Uri\UrlBuilder;
use App\Infrastructure\Validator\ChainedValidator;
use Doctrine\DBAL\DriverManager;
use Twig\Environment as Twig;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

$projectRoot = realpath('..');
$twig = new Twig(
    new FilesystemLoader($projectRoot.'/templates/email'),
    ['cache' => false]
);

$frontendUrlBuilder = new UrlBuilder('http://localhost:8080');
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
        'host' => 'smtp.sendgrid.net',
        'port' => 25,
        'username' => 'apikey',
        'password' => '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',
        'fromEmail' => 'murat@narsana.ru',
    ],
    $twig
);

$eventManager = new EventManager();
$eventManager->subscribe(TodoListCreatedEvent::NAME, new TodoListCreatedNotificationSender($notificationSender));
$eventManager->subscribe(TodoListDeletedEvent::NAME, new TodoListDeletedNotificationSender($notificationSender));

$commandBus = new CommandBus(
    new HandlerResolver(
        new TodoListManager(
            new TodoListDbalRepository(
                DriverManager::getConnection(['url' => 'mysql://root@mysql-dev:3306/app_dev']),
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
