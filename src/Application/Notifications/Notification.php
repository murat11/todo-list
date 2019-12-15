<?php declare(strict_types=1);

namespace App\Application\Notifications;

class Notification
{
    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $recipients;

    /**
     * @param string[] $recipients
     * @param string $subject
     * @param string $body
     */
    public function __construct(array $recipients, string $subject, string $body)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->recipients = $recipients;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }
}
