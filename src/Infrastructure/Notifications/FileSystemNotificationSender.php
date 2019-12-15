<?php declare(strict_types=1);

namespace App\Infrastructure\Notifications;

use App\Application\Notifications\Notification;
use App\Application\Notifications\NotificationSenderInterface;

class FileSystemNotificationSender implements NotificationSenderInterface
{
    /**
     * @var string
     */
    private $file;

    /**
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * @param Notification $notification
     */
    public function send(Notification $notification)
    {
        $notificationContents = $notification->getSubject() . PHP_EOL;
        $notificationContents .= implode(', ', $notification->getRecipients()) . PHP_EOL;
        $notificationContents .= $notification->getBody() . PHP_EOL . PHP_EOL;

        $fp = fopen($this->file, 'a');
        fwrite($fp, $notificationContents);
        fclose($fp);
    }
}
