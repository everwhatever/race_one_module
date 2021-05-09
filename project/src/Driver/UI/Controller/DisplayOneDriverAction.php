<?php

namespace App\Driver\UI\Controller;


use App\Driver\Application\Dto\DriverResults;
use App\Driver\Application\Message\Query\GetOneDriverQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class DisplayOneDriverAction extends AbstractController
{
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @Route("/driver/{id}", name="app_driver")
     * @param int $id
     * @return Response
     */
    public function displayOneDriverProfileAction(int $id): Response
    {
        $results = $this->query($id);

        return $this->render('driver/show_driver_profile.html.twig', [
            'driverEmail' => $results->getDriverEmail(),
            'results' => $results->getResults()
        ]);
    }

    /**
     * @param int $id
     * @return DriverResults
     */
    private function query(int $id): DriverResults
    {
        $message = new GetOneDriverQuery($id);
        $envelope = $this->queryBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        /** @var DriverResults $results */
        return $handledStamp->getResult();
    }
}