<?php declare(strict_types=1);

namespace App\Infrastructure\EventDispatcher;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

class SerializeResponseListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelView(ViewEvent $event)
    {
        $serialized = $this->serializer->serialize($event->getControllerResult(), 'json');
        if (!$event->hasResponse()) {
            $event->setResponse(new Response());
        }
        $event->getResponse()->setContent($serialized);
    }
}
