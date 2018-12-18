<?php

namespace App\Service;

/**
 * Class MailLogin
 * @package App\Service
 */
class MailLogin extends Mail implements MailInterface
{
    public function prepareEmail() : void
    {
        $this->setSubjectEmail('Match Making : Vos identifiants.');
        $this->setRecipientEmail($this->getOptions()['email']);
        $this->setTemplateEmail('emails/registration.html.twig');
    }

    public function sendEmail() : void
    {
        $message = (new \Swift_Message($this->getSubjectEmail()))
            ->setFrom([$this->getSenderEmail() => $this->getSenderName()])
            ->setTo($this->getRecipientEmail())
            ->setBody(
                $this->getTemplating()->render(
                    $this->getTemplateEmail(),
                    $this->getOptions()
                ),
                $this->getContentType()
            );
        $this->getMailer()->send($message);
    }
}
