<?php


namespace App\MessageHandler\Query;


use App\Message\Query\GetOneLeagueQuery;
use App\Repository\LeagueRepository;
use App\Results\Dto\League;
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