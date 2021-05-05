<?php


namespace App\Controller;

use App\Message\Query\GetDriversQuery;
use App\Message\Query\GetOneDriverQuery;
use App\Results\Dto\DriverResults;
use App\Results\Dto\Drivers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class DriverController extends AbstractController
{

    /**
     * @Route("/driver/{id}", name="app_driver")
     * @param int $id
     * @param MessageBusInterface $queryBus
     * @return Response
     */
    public function displayOneDriverProfileAction(int $id, MessageBusInterface $queryBus): Response
    {
        $message = new GetOneDriverQuery($id);
        $envelope = $queryBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        /** @var DriverResults $results */
        $results = $handledStamp->getResult();


        return $this->render('driver/show_driver_profile.html.twig', [
            'driverEmail' => $results->getDriverEmail(),
            'results' => $results->getResults()
        ]);
    }

    /**
     * @Route("/drivers", name="app_all_drivers")
     *
     * @param MessageBusInterface $queryBus
     * @return Response
     */
    public function displayAllDriversProfilesAction(MessageBusInterface $queryBus): Response
    {
        $message = new GetDriversQuery();
        $envelope = $queryBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        /** @var Drivers $driversDto */
        $driversDto = $handledStamp->getResult();

        return $this->render('driver/display_all_drivers.html.twig', [
            'drivers' => $driversDto->getDrivers()
        ]);
    }
}