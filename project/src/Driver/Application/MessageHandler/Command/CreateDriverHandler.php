<?php

declare(strict_types=1);

namespace App\Driver\Application\MessageHandler\Command;

use App\Driver\Application\Message\Command\CreateDriverCommand;
use App\Driver\Domain\Model\Driver;
use App\Driver\Infrastructure\Service\DriverCreatorStrategy\DriverCreator;
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
