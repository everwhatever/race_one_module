<?php


namespace App\Race\Application\MessageHandler\Query;


use App\Race\Application\Dto\EachRaceResult;
use App\Race\Application\Dto\RaceResults;
use App\Race\Application\Message\Query\GetOneRaceQuery;
use App\Race\Infrastructure\Repository\RaceRepository;
use App\Race\Infrastructure\Repository\TimeRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetOneRaceHandler implements MessageHandlerInterface
{
    private RaceRepository $raceRepository;
    private TimeRepository $timeRepository;

    public function __construct(RaceRepository $raceRepository, TimeRepository $timeRepository)
    {
        $this->raceRepository = $raceRepository;
        $this->timeRepository = $timeRepository;
    }

    public function __invoke(GetOneRaceQuery $query): RaceResults
    {
        $race = $this->raceRepository->findOneBy(['id' => $query->getRaceId()]);
        $times = $this->timeRepository->findBy(['races' => $race]);

        $times = $this->sortTimes($times);

        $raceResults = new RaceResults($race->getName());
        foreach ($times as $time) {
            $raceResults->addResult(EachRaceResult::create($time->getPosition(), $time->getDrivers()->getEmail(),
                $time->getTime()));
        }

        return $raceResults;
    }

    private function sortTimes(array $times): array
    {
        usort($times, function ($a, $b) {
            return strcmp($a->getTime(), $b->getTime());
        });
        return $times;
    }

}