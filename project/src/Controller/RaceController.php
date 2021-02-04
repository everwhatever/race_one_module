<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Entity\Race;
use App\Form\GetEmailsFormType;
use App\Services\FetchDriversService;
use App\Services\RaceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;




class RaceController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private FetchDriversService $driversService;
    private RaceService $raceService;


    public function __construct(EntityManagerInterface $entityManager, FetchDriversService $driversService, RaceService $raceService)
    {
        $this->entityManager = $entityManager;
        $this->driversService = $driversService;
        $this->raceService = $raceService;
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
        $repository = $this->entityManager->getRepository(Driver::class);

        $form = $this->createForm(GetEmailsFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $race = $this->driversService->fetchDriversByEmail($form, $repository);

            $this->entityManager->persist($race);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_race',['id'=>$race->getId()]);
        }

        return $this->render('race/choose_drivers.html.twig',[
            'form' => $form->createView()
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
        $repository = $this->entityManager->getRepository(Race::class);
        $race = $repository->findOneBy(['id'=>$id]);

        $times = $this->raceService->setSimulatedParams($race);

        return $this->render('race/race.html.twig',[
            'race' => $race,
            'times' => $times
        ]);
    }
}
