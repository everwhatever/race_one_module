<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Services\DriverCreatorStrategy\AdminCreatorStrategy;
use App\Services\DriverCreatorStrategy\DriverCreator;
use App\Services\DriverCreatorStrategy\NormalDriverCreatorStrategy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{

    private NormalDriverCreatorStrategy $normalDriverCreatorStrategy;
    private AdminCreatorStrategy $adminCreatorStrategy;

    public function __construct(NormalDriverCreatorStrategy $normalDriverCreatorStrategy,
                                AdminCreatorStrategy $adminCreatorStrategy)
    {
        $this->normalDriverCreatorStrategy = $normalDriverCreatorStrategy;
        $this->adminCreatorStrategy = $adminCreatorStrategy;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $driver = $form->getData();
            $driver->setPassword(
                $passwordEncoder->encodePassword(
                    $driver,
                    $form->get('plainPassword')->getData()
                )
            );


            $driverCreator = new DriverCreator($this->normalDriverCreatorStrategy);
            $driverCreator->create($driver);
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $driver,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/adminregister", name="app_register_admin")
     * @IsGranted("ROLE_USER")
     */
    public function registerAdmin(Request $request, UserPasswordEncoderInterface $passwordEncoder,
                                  GuardAuthenticatorHandler $guardHandler,
                                  LoginFormAuthenticator $authenticator): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $driver = $form->getData();
            $driver->setPassword(
                $passwordEncoder->encodePassword(
                    $driver,
                    $form->get('plainPassword')->getData()
                )
            );

            $driverCreator = new DriverCreator($this->adminCreatorStrategy);
            $driverCreator->create($driver);
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $driver,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
