<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 09. 10.
 * Time: 13:02
 */

namespace MedevOffice\Services\Notification\Actions;


use MedevSlim\Core\Action\Repository\Twig\APITwigRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class SendMailNotification
 * @package MedevOffice\Services\Notification\Actions
 */
abstract class SendMailNotification extends APITwigRepositoryAction
{
    /**
     * @var string
     */
    protected $template;
    /**
     * @var string
     */
    protected $subject;
    /**
     * @var string[]
     */
    private $recipients;


    /**
     * @param string[] $recipients
     */
    protected function setRecipients($recipients)
    {
        $this->recipients = $recipients;
    }

    protected function clearRecipients()
    {
        $this->recipients = [];
    }

    protected function addRecipient($recipient)
    {
        $this->recipients[] = $recipient;
    }

    /**
     * @param array $data
     * @throws InternalServerException
     */
    protected function sendMailNotification($data = [])
    {
        $this->info("Initiating notification mail...");

        $mail = new PHPMailer(true);
        $mailConfig = $this->config["application"]["notification"]["mail"];
        $mailBody = $this->render($this->template, $data);

        try {
            $from = $mailConfig["from"];
            $mail->setFrom($from["email"], $from["name"]);
            foreach ($this->recipients as $recipient) {
                $mail->addAddress($recipient);
            }
            $mail->addCC($this->config["administrator"]["email"]);
            foreach ($mailConfig["bcc"] as $bccAddress) {
                $mail->addBCC($bccAddress);
            }

            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body = $mailBody;

            $this->info("Sending mail notification.");
            $mail->send();
            $this->info("Mail notification sent successfully.");
        } catch (Exception $e) {
            throw new InternalServerException("Mail notification can not be sent: " . $mail->ErrorInfo);
        }
    }
}
