<?php

namespace App\Service;

class Mail
{
    /**
     * @var string
     */
    private $recipientEmail;

    /**
     * @var
     */
    private $recipientName;

    /**
     * @var string
     */
    private $senderEmail;

    /**
     * @var string
     */
    private $subjectEmail;

    /**
     * @var string
     */
    private $templateEmail;

    /**
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Mail constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     * @param string $adminEmail
     * @param string $adminGlobalName
     */
    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $templating,
        string $adminEmail,
        string $adminGlobalName
    ) {
        $this->mailer = $mailer;
        $this->recipientEmail = $adminEmail;
        $this->recipientName = $adminGlobalName;
        $this->templating = $templating;
    }

    /**
     * @return string
     */
    public function getRecipientEmail(): string
    {
        return $this->recipientEmail;
    }

    /**
     * @param string $recipientEmail
     * @return Mail
     */
    public function setRecipientEmail(string $recipientEmail): Mail
    {
        $this->recipientEmail = $recipientEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecipientName()
    {
        return $this->recipientName;
    }

    /**
     * @param mixed $recipientName
     * @return Mail
     */
    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    /**
     * @param string $senderEmail
     * @return Mail
     */
    public function setSenderEmail(string $senderEmail): Mail
    {
        $this->senderEmail = $senderEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubjectEmail(): string
    {
        return $this->subjectEmail;
    }

    /**
     * @param string $subjectEmail
     * @return Mail
     */
    public function setSubjectEmail(string $subjectEmail): Mail
    {
        $this->subjectEmail = $subjectEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateEmail(): string
    {
        return $this->templateEmail;
    }

    /**
     * @param string $templateEmail
     * @return Mail
     */
    public function setTemplateEmail(string $templateEmail): Mail
    {
        $this->templateEmail = $templateEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     * @return Mail
     */
    public function setContentType(string $contentType): Mail
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return Mail
     */
    public function setOptions(array $options): Mail
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return \Twig_Environment
     */
    public function getTemplating(): \Twig_Environment
    {
        return $this->templating;
    }

    /**
     * @param \Twig_Environment $templating
     * @return Mail
     */
    public function setTemplating(\Twig_Environment $templating): Mail
    {
        $this->templating = $templating;
        return $this;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer(): \Swift_Mailer
    {
        return $this->mailer;
    }

    /**
     * @param \Swift_Mailer $mailer
     * @return Mail
     */
    public function setMailer(\Swift_Mailer $mailer): Mail
    {
        $this->mailer = $mailer;
        return $this;
    }
}
