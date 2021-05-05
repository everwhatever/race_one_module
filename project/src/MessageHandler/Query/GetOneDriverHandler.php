<?php


namespace App\MessageHandler\Query;


use App\Message\Query\GetOneDriverQuery;
use App\Repository\DriverRepository;
use App\Repository\TimeRepository;
use App\Results\Dto\DriverResults;
use App\Results\Dto\EachDriverResult;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetOneDriverHandler implements MessageHandlerInterface
{
    private DriverRepository $driverRepository;
    private TimeRepository $timeRepository;

    public function __construct(DriverRepository $driverRepository, TimeRepository $timeRepository)
    {
        $this->driverRepository = $driverRepository;
        $this->timeRepository = $timeRepository;
    }

    public function __invoke(GetOneDriverQuery $query): DriverResults
    {
        $driver = $this->driverRepository->findOneBy(['id' => $query->getId()]);
        $times = $this->timeRepository->findBy(['drivers' => $driver]);

        $results = new DriverResults($driver->getEmail());
        foreach ($times as $time) {
            $results->addResult(EachDriverResult::create($time->getPosition(), $time->getRaces()->getName(),
                $time->getTime(), $time->getRaces()->getId()));
        }

        return $results;
    }

}