<?php


namespace App\League\Infrastructure\Service;


use App\League\Domain\Model\League;
use App\Race\Domain\Model\Race;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class RaceSaver
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $races
     * @param array $drivers
     * @param League $league
     */
    public function saveRaces(array $races, array $drivers, League $league): void
    {
        /** @var Race $race */
        foreach ($races as $race) {
            $this->addDriversToRace($drivers, $race);
            $race->setLeague($league);
            $race->setDate(new DateTime());
            $this->entityManager->persist($race);
        }
        $this->entityManager->flush();
    }

    /**
     * @param array $drivers
     * @param Race $race
     */
    private function addDriversToRace(array $drivers, Race $race): void
    {
        foreach ($drivers as $driver) {
            $race->addDriver($driver);
        }
    }
}