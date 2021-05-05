<?php


namespace App\MessageHandler\Query;


use App\Message\Query\GetDriversQuery;
use App\Repository\DriverRepository;
use App\Results\Dto\Driver;
use App\Results\Dto\Drivers;
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