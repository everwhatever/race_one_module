<?php

namespace App\Services;

use App\Entity\Race;
use App\Repository\DriverRepository;
use DateTime;
use Symfony\Component\Form\FormInterface;

class FetchDriversService
{
    /**
     * @param FormInterface $form
     * @param DriverRepository $repository
     * @return Race
     */
    public function fetchDriversByEmail(FormInterface $form, DriverRepository $repository): Race
    {
        $emailsArray = [];
        $emails = $form['email']->getData();
        foreach ($emails as $email) {
            array_push($emailsArray, $email->getEmail());
        }

        return $this->createRace($emailsArray, $repository);
    }

    /**
     * @param array $emailsArray
     * @param DriverRepository $repository
     * @return Race
     */
    private function createRace(array $emailsArray, DriverRepository $repository): Race
    {
        $race = new Race();
        $race->setDate(new DateTime());
        foreach ($emailsArray as $item){
            $race->addDriver($repository->findOneBy(['email'=>$item]));
        }

        return $race;
    }
}