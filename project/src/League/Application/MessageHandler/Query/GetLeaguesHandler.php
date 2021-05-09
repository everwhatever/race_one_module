<?php


namespace App\League\Application\MessageHandler\Query;


use App\League\Application\Dto\League;
use App\League\Application\Dto\Leagues;
use App\League\Application\Message\Query\GetLeaguesQuery;
use App\League\Infrastructure\Repository\LeagueRepository;
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