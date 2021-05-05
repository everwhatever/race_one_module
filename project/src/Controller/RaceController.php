<?php

namespace App\Controller;

use App\Form\CreateRaceType;
use App\Message\Command\CreateRaceCommand;
use App\Message\Query\GetOneRaceQuery;
use App\Message\Query\GetRacesQuery;
use App\Results\Dto\RaceResults;
use App\Services\FormDataGetter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;


class RaceController extends AbstractController
{
    private FormDataGetter $dataGetter;

    public function __construct(FormDataGetter $dataGetter)
    {
        $this->dataGetter = $dataGetter;
    }


    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('race/index.html.twig');
    }

    /**
     * @Route("/choose", name="app_choose_drivers")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function createAction(Request $request, MessageBusInterface $commandBus): Response
    {
        $form = $this->createForm(CreateRaceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $driversIds = $this->dataGetter->getDriversIds($formData['drivers']);

            $message = new CreateRaceCommand($driversIds, $formData['name']);
            $envelope = $commandBus->dispatch($message);
            /** @var HandledStamp $handledStamp */
            $handledStamp = $envelope->last(HandledStamp::class);
            $result = $handledStamp->getResult();

            return $this->redirectToRoute("app_show_one_race", ['id' => $result->getId()]);
        }

        return $this->render('race/choose_drivers.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/races", name="app_all_races")
     * @param MessageBusInterface $queryBus
     * @return Response
     */
    public function displayAllRacesAction(MessageBusInterface $queryBus): Response
    {
        $message = new GetRacesQuery();
        $envelope = $queryBus->dispatch($message);
        /** @var HandledStamp $handleStamp */
        $handleStamp = $envelope->last(HandledStamp::class);
        $racesDto = $handleStamp->getResult();

        return $this->render('race/display_all_races.html.twig', [
            'races' => $racesDto->getRaces()
        ]);
    }

    /**
     * @Route("/show/race/{id}", name="app_show_one_race")
     * @param int $id
     * @param MessageBusInterface $queryBus
     * @return Response
     */
    public function displayOneRaceAction(int $id, MessageBusInterface $queryBus): Response
    {
        $message = new GetOneRaceQuery($id);
        $envelope = $queryBus->dispatch($message);
        /** @var HandledStamp $handleStamp */
        $handleStamp = $envelope->last(HandledStamp::class);
        /** @var RaceResults $racesDto */
        $racesDto = $handleStamp->getResult();


        return $this->render('race/show_one_race.html.twig', [
            'raceName' => $racesDto->getRaceName(),
            'results' => $racesDto->getResults()
        ]);
    }
}
