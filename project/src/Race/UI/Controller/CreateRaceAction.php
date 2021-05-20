<?php

namespace App\Race\UI\Controller;

use App\Race\Application\Message\Command\CreateRaceCommand;
use App\Shared\Infrastructure\Form\CreateRaceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class CreateRaceAction extends AbstractController
{
    private MessageBusInterface $commandBus;

    public function __construct( MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/choose", name="app_choose_drivers")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->createForm(CreateRaceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $result = $this->command($formData['driversIds'], $formData['name']);

            return $this->redirectToRoute("app_show_one_race", ['id' => $result->getId()]);
        }

        return $this->render('race/choose_drivers.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param array $driversIds
     * @param $name
     * @return mixed
     */
    private function command(array $driversIds, $name): mixed
    {
        $message = new CreateRaceCommand($driversIds, $name);
        $envelope = $this->commandBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        return $handledStamp->getResult();
    }
}