<?php

namespace App\Service;

class MailLogin extends Mail implements MailInterface
{
    public function prepareEmail() : void
    {
        $this->setSubjectEmail('Vos accès à l\'application Match Making.');
        $this->setSenderEmail($this->getOptions()['email']);
        $this->setTemplateEmail('emails/registration.html.twig');
    }

    public function sendEmail() : void
    {
        $message = (new \Swift_Message($this->getSubjectEmail()))
            ->setFrom([$this->getRecipientEmail() => $this->getRecipientName()])
            ->setTo($this->getSenderEmail())
            ->setBody($this->getTemplating()->render($this->getTemplateEmail(), $this->getOptions()), $this->getContentType());
        $this->getMailer()->send($message);
    }
}
