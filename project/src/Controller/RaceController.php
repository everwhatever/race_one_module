<?php

namespace App\Controller;

use App\Entity\Race;
use App\Entity\Time;
use App\Form\CreateRaceType;
use App\Services\Time\TimeCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class RaceController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private TimeCreator $timeService;


    public function __construct(EntityManagerInterface $entityManager, TimeCreator $timeService)
    {
        $this->entityManager = $entityManager;
        $this->timeService = $timeService;
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
     * @return Response
     */
    public function chooseDriversAction(Request $request): Response
    {
        $form = $this->createForm(CreateRaceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $race = $form->getData();
            $race->setDate(new \DateTime());

            $this->entityManager->persist($race);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_race', ['id' => $race->getId()]);
        }

        return $this->render('race/choose_drivers.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/race/{id}", name="app_race")
     * @IsGranted("ROLE_USER")
     * @param int $id
     * @return Response
     */
    public function raceAction(int $id): Response
    {
        $raceRepository = $this->entityManager->getRepository(Race::class);
        $race = $raceRepository->findOneBy(['id' => $id]);

        $times = $this->timeService->setSimulatedParams($race);

        return $this->render('race/race.html.twig', [
            'race' => $race,
            'times' => $times
        ]);
    }

    /**
     * @Route("/races", name="app_all_races")
     * @return Response
     */
    public function displayAllRacesAction(): Response
    {
        $raceRepository = $this->entityManager->getRepository(Race::class);
        $races = $raceRepository->findAll();

        return $this->render('race/display_all_races.html.twig', [
            'races' => $races
        ]);
    }

    /**
     * @Route("/show/race/{id}", name="app_show_one_race")
     * @param int $id
     * @return Response
     */
    public function displayOneRaceAction(int $id): Response
    {
        $raceRepository = $this->entityManager->getRepository(Race::class);
        $race = $raceRepository->findOneBy(['id' => $id]);

        $timeRepository = $this->entityManager->getRepository(Time::class);
        $times = $timeRepository->findBy(['races' => $race]);

        return $this->render('race/show_one_race.html.twig', [
            'race' => $race,
            'times' => $times
        ]);
    }
}
