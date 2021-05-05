<?php


namespace App\MessageHandler\Query;


use App\Message\Query\GetLeaguesQuery;
use App\Repository\LeagueRepository;
use App\Results\Dto\League;
use App\Results\Dto\Leagues;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetLeaguesHandler implements MessageHandlerInterface
{
    private LeagueRepository $leagueRepository;

    public function __construct(LeagueRepository $leagueRepository)
    {
        $this->leagueRepository = $leagueRepository;
    }

    public function __invoke(GetLeaguesQuery $query): Leagues
    {
        $leagues = $this->leagueRepository->findAll();

        $leaguesDto = new Leagues();
        foreach ($leagues as $league) {
            $leaguesDto->addLeague(League::create($league->getId(), $league->getName(), $league->getPositions()));
        }

        return $leaguesDto;
    }

}