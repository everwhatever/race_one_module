<?php

namespace App\MessageHandler\Query;

use App\Message\Query\GetRacesQuery;
use App\Repository\RaceRepository;
use App\Results\Dto\Race;
use App\Results\Dto\Races;
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