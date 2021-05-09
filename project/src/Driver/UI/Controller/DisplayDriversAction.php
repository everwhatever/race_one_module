<?php

namespace App\Driver\UI\Controller;


use App\Driver\Application\Dto\Drivers;
use App\Driver\Application\Message\Query\GetDriversQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class DisplayDriversAction extends AbstractController
{
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @Route("/drivers", name="app_all_drivers")
     *
     * @return Response
     */
    public function displayAllDriversProfilesAction(): Response
    {
        $driversDto = $this->query();

        return $this->render('driver/display_all_drivers.html.twig', [
            'drivers' => $driversDto->getDrivers()
        ]);
    }

    /**
     * @return Drivers
     */
    private function query(): Drivers
    {
        $message = new GetDriversQuery();
        $envelope = $this->queryBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        /** @var Drivers $driversDto */
        return $handledStamp->getResult();
    }
}