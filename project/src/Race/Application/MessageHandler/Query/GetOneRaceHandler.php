<?php


namespace App\Race\Application\MessageHandler\Query;


use App\Driver\Infrastructure\Repository\DriverRepository;
use App\Race\Application\Dto\EachRaceResult;
use App\Race\Application\Dto\RaceResults;
use App\Race\Application\Message\Query\GetOneRaceQuery;
use App\Race\Domain\Model\Time;
use App\Race\Infrastructure\Repository\RaceRepository;
use App\Race\Infrastructure\Repository\TimeRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetOneRaceHandler implements MessageHandlerInterface
{
    private RaceRepository $raceRepository;
    private TimeRepository $timeRepository;
    private DriverRepository $driverRepository;

    //TODO: usunąć jakoś DriverRepository
    public function __construct(RaceRepository $raceRepository, TimeRepository $timeRepository,
                                DriverRepository $driverRepository)
    {
        $this->raceRepository = $raceRepository;
        $this->timeRepository = $timeRepository;
        $this->driverRepository = $driverRepository;
    }

    public function __invoke(GetOneRaceQuery $query): RaceResults
    {
        $race = $this->raceRepository->findOneBy(['id' => $query->getRaceId()]);
        $times = $this->timeRepository->findBy(['races' => $race]);

        $times = $this->sortTimes($times);

        $raceResults = new RaceResults($race->getName());
        /** @var Time $time */
        foreach ($times as $time) {
            $raceResults->addResult(new EachRaceResult($time->getPosition(),
                $time->getTime(), $this->driverRepository->findOneBy(['id'=>$time->getDriverId()])->getEmail()));
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