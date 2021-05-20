<?php

namespace App\League\UI\Controller;


use App\League\Application\Message\Command\CreateLeagueCommand;
use App\Shared\Infrastructure\Form\CreateLeagueType;
use App\Shared\Infrastructure\Service\FormDataGetter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreateLeagueAction extends AbstractController
{
    private MessageBusInterface $commandBus;
    private FormDataGetter $dataGetter;

    public function __construct(MessageBusInterface $commandBus, FormDataGetter $dataGetter)
    {
        $this->commandBus = $commandBus;
        $this->dataGetter = $dataGetter;
    }


    /**
     * @Route("/league/create", name="league_create")
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function createLeagueAction(Request $request): Response
    {
        $form = $this->createForm(CreateLeagueType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $racesNames = $this->dataGetter->getRacesNames($formData['racesNames']);

            $this->command($formData['driversIds'], $racesNames, $formData['name']);


            return $this->redirectToRoute("league_display_all");
        }

        return $this->render("league/create_league.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param array $driversIds
     * @param array $racesNames
     * @param $name
     */
    private function command(array $driversIds, array $racesNames, $name): void
    {
        $message = new CreateLeagueCommand($driversIds, $racesNames, $name);
        $this->commandBus->dispatch($message);
    }
}