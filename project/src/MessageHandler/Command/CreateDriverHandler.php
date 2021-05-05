<?php


namespace App\MessageHandler\Command;


use App\Entity\Driver;
use App\Message\Command\CreateDriverCommand;
use App\Services\DriverCreatorStrategy\DriverCreator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateDriverHandler implements MessageHandlerInterface
{
    private DriverCreator $driverCreator;

    public function __construct(DriverCreator $driverCreator)
    {
        $this->driverCreator = $driverCreator;
    }

    public function __invoke(CreateDriverCommand $command): Driver
    {
        return $this->driverCreator->create($command->getEmail(), $command->getPassword(), $command->getStrategy());
    }

}