<?php


namespace App\Controller\League;

use App\Form\League\CreateLeagueType;
use App\Message\Command\CreateLeagueCommand;
use App\Message\Query\GetLeaguesQuery;
use App\Message\Query\GetOneLeagueQuery;
use App\Results\Dto\Leagues;
use App\Services\FormDataGetter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;


class LeagueController extends AbstractController
{
    private FormDataGetter $dataGetter;

    public function __construct(FormDataGetter $dataGetter)
    {
        $this->dataGetter = $dataGetter;
    }


    /**
     * @Route("/league/create", name="league_create")
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function createLeagueAction(Request $request, MessageBusInterface $messageBus): Response
    {
        $form = $this->createForm(CreateLeagueType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $racesNames = $this->dataGetter->getRacesNames($formData['races']);
            $driversIds = $this->dataGetter->getDriversIds($formData['drivers']);

            $message = new CreateLeagueCommand($driversIds, $racesNames, $formData['name']);
            $messageBus->dispatch($message);


            return $this->redirectToRoute("league_display_all");
        }

        return $this->render("league/create_league.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param MessageBusInterface $queryBus
     * @return Response
     *
     * @Route("/league/display", name="league_display_all")
     */
    public function displayLeaguesAction(MessageBusInterface $queryBus): Response
    {
        $message = new GetLeaguesQuery();
        $envelope = $queryBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        /** @var Leagues $leagues */
        $leagues = $handledStamp->getResult();

        return $this->render("league/display_all.html.twig", [
            'leagues' => $leagues->getLeagues()
        ]);
    }

    /**
     * @param int $id
     * @param MessageBusInterface $queryBus
     * @return Response
     *
     * @Route("/league/display/{id}", name="league_display_one")
     */
    public function displayOneAction(int $id, MessageBusInterface $queryBus): Response
    {
        $message = new GetOneLeagueQuery($id);
        $envelope = $queryBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        $league = $handledStamp->getResult();

        return $this->render("league/display_one.html.twig", [
            'league' => $league
        ]);
    }


}