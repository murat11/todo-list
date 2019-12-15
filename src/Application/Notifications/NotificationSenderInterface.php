<?php declare(strict_types=1);

namespace App\Application\Notifications;

interface NotificationSenderInterface
{
    /**
     * @param Notification $notification
     */
    public function send(Notification $notification);
}
