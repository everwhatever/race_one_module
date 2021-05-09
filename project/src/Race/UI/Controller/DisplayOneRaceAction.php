<?php

namespace App\Race\UI\Controller;


use App\Race\Application\Dto\RaceResults;
use App\Race\Application\Message\Query\GetOneRaceQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class DisplayOneRaceAction extends AbstractController
{
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }


    /**
     * @Route("/show/race/{id}", name="app_show_one_race")
     * @param int $id
     * @return Response
     */
    public function displayOneRaceAction(int $id): Response
    {
        $racesDto = $this->query($id);


        return $this->render('race/show_one_race.html.twig', [
            'raceName' => $racesDto->getRaceName(),
            'results' => $racesDto->getResults()
        ]);
    }

    /**
     * @param int $id
     * @return RaceResults
     */
    private function query(int $id): RaceResults
    {
        $message = new GetOneRaceQuery($id);
        $envelope = $this->queryBus->dispatch($message);
        /** @var HandledStamp $handleStamp */
        $handleStamp = $envelope->last(HandledStamp::class);
        /** @var RaceResults $racesDto */
        return $handleStamp->getResult();
    }
}