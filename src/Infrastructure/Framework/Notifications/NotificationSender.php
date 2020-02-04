<?php declare(strict_types=1);

namespace App\Infrastructure\Framework\Notifications;

use App\Application\Notifications\Notification;
use App\Application\Notifications\NotificationSenderInterface;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Environment as Twig;

class NotificationSender implements NotificationSenderInterface
{
    /**
     * @var Twig
     */
    private $twig;

    /**
     * @var array
     */
    private $mailerParameters;


    /**
     * @param array $mailerParameters
     * @param Twig $twig
     */
    public function __construct(array $mailerParameters, Twig $twig)
    {
        $this->twig = $twig;
        $this->mailerParameters = $mailerParameters;
    }

    /**
     * @param Notification $notification
     */
    public function send(Notification $notification)
    {
        foreach ($notification->getRecipients() as $email) {
            $mailerMessage = $this->prepareMailMessage($email);

            $templatePath = $notification->getType() . '.html.twig';
            $templateWrapper = $this->twig->load($templatePath);
            if ($templateWrapper->hasBlock('subject')) {
                $mailerMessage->Subject = $templateWrapper->renderBlock('subject', $notification->getProperties());
            }

            if ($templateWrapper->hasBlock('body_html')) {
                $mailerMessage->Body = $templateWrapper->renderBlock('body_html', $notification->getProperties());
            }

            if ($templateWrapper->hasBlock('body_text')) {
                $mailerMessage->AltBody = $templateWrapper->renderBlock('body_text', $notification->getProperties());
            }

            $mailerMessage->send();
        }
    }

    /**
     * @param string $email
     *
     * @return PHPMailer
     */
    private function prepareMailMessage(string $email): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->addAddress($email);
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $this->mailerParameters['host'];
        $mail->Username = $this->mailerParameters['username'];
        $mail->Password = $this->mailerParameters['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $this->mailerParameters['port'];
        if (isset($this->mailerParameters['fromEmail'])) {
            $mail->setFrom($this->mailerParameters['fromEmail'], $this->mailerParameters['fromName'] ?? '');
        }
        $mail->isHTML(true);

        return $mail;
    }
}
