<?php


namespace App\Controller\League;

use App\Entity\League;
use App\Form\League\CreateLeagueType;
use App\Services\League\CreateLeagueService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class LeagueController
 * @package App\Controller\League
 *
 * @IsGranted("ROLE_ADMIN")
 */
class LeagueController extends AbstractController
{
    private CreateLeagueService $leagueService;
    private EntityManagerInterface $entityManager;

    public function __construct(CreateLeagueService $leagueService, EntityManagerInterface $entityManager)
    {
        $this->leagueService = $leagueService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/league/create", name="league_create")
     */
    public function createLeagueAction(Request $request): Response
    {
        $form = $this->createForm(CreateLeagueType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var League $league */
            $league = $form->getData();
            $this->leagueService->createLeague($league);
            return $this->redirectToRoute("league_display_all");
        }

        return $this->render("league/create_league.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/league/display", name="league_display_all")
     */
    public function displayLeaguesAction(): Response
    {
        $repository = $this->entityManager->getRepository(League::class);
        $leagues = $repository->findAll();

        return $this->render("league/display_all.html.twig", [
            'leagues' => $leagues
        ]);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Route("/league/display/{id}", name="league_display_one")
     */
    public function displayOneAction(int $id): Response
    {
        $repository = $this->entityManager->getRepository(League::class);
        $league = $repository->findOneBy(['id' => $id]);

        return $this->render("league/display_one.html.twig", [
            'league' => $league
        ]);
    }


}