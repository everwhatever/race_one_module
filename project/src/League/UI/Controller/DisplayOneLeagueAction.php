<?php

namespace App\League\UI\Controller;


use App\League\Application\Message\Query\GetOneLeagueQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class DisplayOneLeagueAction extends AbstractController
{
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Route("/league/display/{id}", name="league_display_one")
     */
    public function displayOneAction(int $id): Response
    {
        $league = $this->query($id);

        return $this->render("league/display_one.html.twig", [
            'league' => $league
        ]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    private function query(int $id): mixed
    {
        $message = new GetOneLeagueQuery($id);
        $envelope = $this->queryBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        return $handledStamp->getResult();
    }
}