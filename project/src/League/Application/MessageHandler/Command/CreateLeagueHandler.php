<?php


namespace App\League\Application\MessageHandler\Command;


use App\Driver\Infrastructure\Repository\DriverRepository;
use App\League\Application\Message\Command\CreateLeagueCommand;
use App\League\Domain\Model\League;
use App\League\Domain\Service\CreateLeagueService;
use App\Race\Domain\Model\Race;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateLeagueHandler implements MessageHandlerInterface
{
    private DriverRepository $driverRepository;
    private CreateLeagueService $leagueService;

    public function __construct(DriverRepository $driverRepository, CreateLeagueService $leagueService)
    {
        $this->driverRepository = $driverRepository;
        $this->leagueService = $leagueService;
    }

    public function __invoke(CreateLeagueCommand $command)
    {
        $drivers = $this->driverRepository->findByIds($command->getDriversIds());
        foreach ($command->getRacesNames() as $raceName) {
            $races[] = Race::create($raceName, $drivers);
        }
        $league = new League();
        foreach ($drivers as $driver) {
            $league->addDriver($driver);
        }
        foreach ($races as $race) {
            $league->addRace($race);
        }
        $league->setName($command->getLeagueName());

        $this->leagueService->createLeague($league);
    }

}