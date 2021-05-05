<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Message\Command\CreateDriverCommand;
use App\Security\LoginFormAuthenticator;
use App\Services\DriverCreatorStrategy\AdminCreatorStrategy;
use App\Services\DriverCreatorStrategy\NormalDriverCreatorStrategy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{

    private NormalDriverCreatorStrategy $normalDriverCreatorStrategy;
    private AdminCreatorStrategy $adminCreatorStrategy;
    private GuardAuthenticatorHandler $authenticatorHandler;
    private LoginFormAuthenticator $loginFormAuthenticator;
    private MessageBusInterface $messageBus;

    public function __construct(NormalDriverCreatorStrategy $normalDriverCreatorStrategy,
                                AdminCreatorStrategy $adminCreatorStrategy,
                                GuardAuthenticatorHandler $authenticatorHandler,
                                LoginFormAuthenticator $loginFormAuthenticator, MessageBusInterface $messageBus)
    {
        $this->normalDriverCreatorStrategy = $normalDriverCreatorStrategy;
        $this->adminCreatorStrategy = $adminCreatorStrategy;
        $this->authenticatorHandler = $authenticatorHandler;
        $this->loginFormAuthenticator = $loginFormAuthenticator;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            $message = new CreateDriverCommand($formData['email'], $form->get('plainPassword')->getData(),
                $this->normalDriverCreatorStrategy);
            $envelope = $this->messageBus->dispatch($message);
            /** @var HandledStamp $handledStamp */
            $handledStamp = $envelope->last(HandledStamp::class);
            $driver = $handledStamp->getResult();

            return $this->authenticatorHandler->authenticateUserAndHandleSuccess(
                $driver,
                $request,
                $this->loginFormAuthenticator,
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
    public function registerAdmin(Request $request): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();


            $message = new CreateDriverCommand($formData['email'], $form->get('plainPassword')->getData(),
                $this->adminCreatorStrategy);
            $envelope = $this->messageBus->dispatch($message);
            /** @var HandledStamp $handledStamp */
            $handledStamp = $envelope->last(HandledStamp::class);
            $driver = $handledStamp->getResult();

            return $this->authenticatorHandler->authenticateUserAndHandleSuccess(
                $driver,
                $request,
                $this->loginFormAuthenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
