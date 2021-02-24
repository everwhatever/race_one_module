<?php


namespace App\Services;

use App\Entity\Driver;
use App\Entity\Race;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class RaceService
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormInterface $form
     * @return Race
     */
    public function createRaceWithDrivers(FormInterface $form): Race
    {
        $information = $this->fetchInformationToCreateRace($form);

        $race = new Race();
        $race->setDate(new DateTime());
        $race->setName($information['raceName']);
        foreach ($information['driversEmails'] as $item) {
            $race->addDriver($information['driversRepository']->findOneBy(['email' => $item]));
        }

        $this->entityManager->persist($race);
        $this->entityManager->flush();

        return $race;
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    private function fetchInformationToCreateRace(FormInterface $form): array
    {
        $information = [];
        $driversRepository = $this->entityManager->getRepository(Driver::class);
        $driversService = new FetchDriversService();
        $driversEmails = $driversService->fetchDriversByEmail($form);
        $raceName = $form['name']->getData();

        if ($raceName === null) {
            $raceName = "default";
        }

        $information['driversRepository'] = $driversRepository;
        $information['driversEmails'] = $driversEmails;
        $information['raceName'] = $raceName;

        return $information;
    }
}