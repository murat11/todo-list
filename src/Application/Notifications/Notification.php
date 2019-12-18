<?php declare(strict_types=1);

namespace App\Application\Notifications;

class Notification
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $properties;

    /**
     * @var array
     */
    private $recipients;

    /**
     * @param string[] $recipients
     * @param string $type
     * @param array $properties
     */
    public function __construct(array $recipients, string $type, array $properties)
    {
        $this->type = $type;
        $this->properties = $properties;
        $this->recipients = $recipients;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return string[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }
}
