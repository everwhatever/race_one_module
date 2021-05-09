<?php

namespace App\Race\UI\Controller;


use App\Race\Application\Message\Query\GetRacesQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class DisplayRacesAction extends AbstractController
{

    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @Route("/races", name="app_all_races")
     * @return Response
     */
    public function displayAllRacesAction(): Response
    {
        $racesDto = $this->query();

        return $this->render('race/display_all_races.html.twig', [
            'races' => $racesDto->getRaces()
        ]);
    }

    /**
     * @return mixed
     */
    private function query(): mixed
    {
        $message = new GetRacesQuery();
        $envelope = $this->queryBus->dispatch($message);
        /** @var HandledStamp $handleStamp */
        $handleStamp = $envelope->last(HandledStamp::class);
        return $handleStamp->getResult();
    }
}