<?php

namespace App\Service;

/**
 * Interface MailInterface
 * @package App\Service
 */
interface MailInterface
{
    public function prepareEmail() : void;

    public function sendEmail() : void;
}
