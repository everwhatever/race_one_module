<?php

declare(strict_types=1);

namespace App\Driver\Application\MessageHandler\Query;

use App\Driver\Application\Dto\Driver;
use App\Driver\Application\Dto\Drivers;
use App\Driver\Application\Message\Query\GetDriversQuery;
use App\Driver\Infrastructure\Repository\DriverRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetDriversHandler implements MessageHandlerInterface
{
    private DriverRepository $driverRepository;

    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function __invoke(GetDriversQuery $query): Drivers
    {
        $drivers = $this->driverRepository->findAll();

        $driversDto = new Drivers();
        foreach ($drivers as $driver) {
            $driversDto->addDriver(Driver::create($driver->getId(), $driver->getEmail()));
        }

        return $driversDto;
    }

}
