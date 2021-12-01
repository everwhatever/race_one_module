<?php

declare(strict_types=1);

namespace App\Driver\Application\MessageHandler\Query;

use App\Driver\Application\Dto\DriverResults;
use App\Driver\Application\Dto\EachDriverResult;
use App\Driver\Application\Message\Query\GetOneDriverQuery;
use App\Driver\Infrastructure\Repository\DriverRepository;
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
        $timesIds = $driver->getTimesIds();

        $results = new DriverResults($driver->getEmail());
        foreach ($timesIds as $timeId) {
            $results->addResult(new EachDriverResult($this->timeRepository->findOneBy(['id'=>$timeId])));
        }

        return $results;
    }

}
