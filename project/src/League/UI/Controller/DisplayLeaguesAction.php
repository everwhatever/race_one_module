<?php

namespace App\League\UI\Controller;


use App\League\Application\Dto\Leagues;
use App\League\Application\Message\Query\GetLeaguesQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class DisplayLeaguesAction extends AbstractController
{
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @return Response
     *
     * @Route("/league/display", name="league_display_all")
     */
    public function displayLeaguesAction(): Response
    {
        $leagues = $this->query();

        return $this->render("league/display_all.html.twig", [
            'leagues' => $leagues->getLeagues()
        ]);
    }

    /**
     * @return Leagues
     */
    private function query(): Leagues
    {
        $message = new GetLeaguesQuery();
        $envelope = $this->queryBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        /** @var Leagues $leagues */
        return $handledStamp->getResult();
    }

}