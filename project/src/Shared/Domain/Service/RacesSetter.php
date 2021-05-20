<?php


namespace App\Shared\Domain\Service;


use App\League\Domain\Model\League;
use App\Race\Domain\Model\Race;
use Doctrine\ORM\EntityManagerInterface;

class RacesSetter
{
    private EntityManagerInterface $entityManager;
    private TimeCreator $timeCreator;

    public function __construct(EntityManagerInterface $entityManager, TimeCreator $timeCreator)
    {
        $this->entityManager = $entityManager;
        $this->timeCreator = $timeCreator;
    }


    public function createRaces(array $racesNames, array $driversIds): array
    {
        $races = [];
        foreach ($racesNames as $raceName) {
            $races[] = Race::create($raceName, $driversIds);
        }
        $this->saveRaces($races);
        $this->createTimes($races);

        return $races;
    }

    public function assignRacesToLeague(array $races, League $league): void
    {
        /** @var Race $race */
        foreach ($races as $race) {
            $league->addRaceId($race->getId());
            $race->setLeagueId($league->getId());
        }
    }

    private function saveRaces(array $races): void
    {
        foreach ($races as $race) {
            $this->entityManager->persist($race);
        }
        $this->entityManager->flush();
    }

    private function createTimes(array $races)
    {
        foreach ($races as $race){
            $this->timeCreator->setSimulatedParams($race);
        }
    }
}