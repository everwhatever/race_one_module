<?php

namespace App\Services;

use Symfony\Component\Form\FormInterface;

class FetchDriversService
{
    /**
     * @param FormInterface $form
     * @return array
     */
    public function fetchDriversByEmail(FormInterface $form): array
    {
        $driversEmails = [];
        $emails = $form['email']->getData();
        foreach ($emails as $email) {
            array_push($driversEmails, $email->getEmail());
        }

        return $driversEmails;
    }
}