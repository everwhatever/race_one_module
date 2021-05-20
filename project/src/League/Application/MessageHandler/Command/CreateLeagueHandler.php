<?php


namespace App\League\Application\MessageHandler\Command;


use App\League\Application\Message\Command\CreateLeagueCommand;
use App\Shared\Domain\Service\LeagueCreator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateLeagueHandler implements MessageHandlerInterface
{
    private LeagueCreator $leagueCreator;

    public function __construct(LeagueCreator $leagueCreator)
    {
        $this->leagueCreator = $leagueCreator;
    }

    public function __invoke(CreateLeagueCommand $command)
    {
        $this->leagueCreator->createLeague($command->getDriversIds(), $command->getRacesNames(),
            $command->getLeagueName());
    }
}