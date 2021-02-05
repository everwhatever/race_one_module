<?php


namespace App\Controller;

use App\Entity\Driver;
use App\Entity\Time;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DriverController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/driver/{id}", name="app_driver")
     * @param int $id
     * @return Response
     */
    public function showDriverProfile(int $id): Response
    {
        $repository = $this->entityManager->getRepository(Driver::class);
        $timeRepository = $this->entityManager->getRepository(Time::class);
        $driver = $repository->findOneBy(['id'=>$id]);
        $times = $timeRepository->findBy(['drivers'=>$driver]);

        return $this->render('driver/show_driver_profile.html.twig',[
            'driver' => $driver,
            'times' => $times
        ]);
    }

    /**
     * @Route("/drivers", name="app_all_drivers")
     *
     * @return Response
     */
    public function displayAllDrivers(): Response
    {
        $repository = $this->entityManager->getRepository(Driver::class);
        $drivers = $repository->findAll();

        return $this->render('driver/display_all_drivers.html.twig',[
           'drivers' => $drivers
        ]);
    }
}