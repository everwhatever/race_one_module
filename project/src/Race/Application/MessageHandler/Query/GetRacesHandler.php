<?php

namespace App\Race\Application\MessageHandler\Query;

use App\Race\Application\Dto\Race;
use App\Race\Application\Dto\Races;
use App\Race\Application\Message\Query\GetRacesQuery;
use App\Race\Infrastructure\Repository\RaceRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetRacesHandler implements MessageHandlerInterface
{
    private RaceRepository $raceRepository;

    public function __construct(RaceRepository $raceRepository)
    {
        $this->raceRepository = $raceRepository;
    }

    public function __invoke(GetRacesQuery $query): Races
    {
        $races = $this->raceRepository->findAll();

        $racesDto = new Races();
        foreach ($races as $race) {
            $racesDto->addRace(Race::createRace($race->getName(), $race->getDate(), $race->getId()));
        }

        return $racesDto;
    }


}