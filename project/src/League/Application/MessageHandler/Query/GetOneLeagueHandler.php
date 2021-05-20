<?php


namespace App\League\Application\MessageHandler\Query;


use App\Driver\Infrastructure\Repository\DriverRepository;
use App\League\Application\Dto\League;
use App\League\Application\Message\Query\GetOneLeagueQuery;
use App\League\Domain\Model\League as LeagueModel;
use App\League\Infrastructure\Repository\LeagueRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetOneLeagueHandler implements MessageHandlerInterface
{
    private LeagueRepository $leagueRepository;
    private DriverRepository $driverRepository;

    public function __construct(LeagueRepository $leagueRepository, DriverRepository $driverRepository)
    {
        $this->leagueRepository = $leagueRepository;
        $this->driverRepository = $driverRepository;
    }

    public function __invoke(GetOneLeagueQuery $query): League
    {
        $league = $this->leagueRepository->findOneBy(['id' => $query->getId()]);

        $positions = $this->changeDriversIdsToEmails($league);

        return League::create($league->getId(), $league->getName(), $positions);
    }


    private function changeDriversIdsToEmails(LeagueModel $league): array
    {
        $positions = [];
        foreach ($league->getPositions() as $id => $position) {
            $positions[$this->driverRepository->findOneBy(['id' => $id])->getEmail()] = $position;
        }
        return $positions;
    }

}