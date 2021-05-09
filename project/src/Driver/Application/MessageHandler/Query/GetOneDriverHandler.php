<?php


namespace App\Driver\Application\MessageHandler\Query;


use App\Driver\Application\Dto\DriverResults;
use App\Driver\Application\Dto\EachDriverResult;
use App\Driver\Application\Message\Query\GetOneDriverQuery;
use App\Driver\Infrastructure\Repository\DriverRepository;
use App\Race\Domain\Model\Time;
use App\Race\Infrastructure\Repository\TimeRepository;
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
        /** @var Time $time */
        foreach ($times as $time) {
            $results->addResult(EachDriverResult::create($time->getPosition(), $time->getRaces()->getName(),
                $time->getTime(), $time->getRaces()->getId()));
        }

        return $results;
    }

}