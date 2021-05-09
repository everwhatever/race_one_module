<?php


namespace App\League\Application\MessageHandler\Query;


use App\League\Application\Dto\League;
use App\League\Application\Message\Query\GetOneLeagueQuery;
use App\League\Infrastructure\Repository\LeagueRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetOneLeagueHandler implements MessageHandlerInterface
{
    private LeagueRepository $leagueRepository;

    public function __construct(LeagueRepository $leagueRepository)
    {
        $this->leagueRepository = $leagueRepository;
    }

    public function __invoke(GetOneLeagueQuery $query): League
    {
        $league = $this->leagueRepository->findOneBy(['id' => $query->getId()]);

        return League::create($league->getId(), $league->getName(), $league->getPositions());
    }

}