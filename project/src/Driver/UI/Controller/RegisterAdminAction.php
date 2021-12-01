<?php

declare(strict_types=1);

namespace App\Driver\UI\Controller;

use App\Driver\Application\Message\Command\CreateDriverCommand;
use App\Driver\Application\Security\LoginFormAuthenticator;
use App\Driver\Infrastructure\Form\RegistrationFormType;
use App\Driver\Infrastructure\Service\DriverCreatorStrategy\AdminCreatorStrategy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegisterAdminAction extends AbstractController
{
    private AdminCreatorStrategy $adminCreatorStrategy;
    
    private GuardAuthenticatorHandler $authenticatorHandler;
    
    private LoginFormAuthenticator $loginFormAuthenticator;
    
    private MessageBusInterface $commandBus;

    public function __construct(AdminCreatorStrategy $adminCreatorStrategy,
                                GuardAuthenticatorHandler $authenticatorHandler,
                                LoginFormAuthenticator $loginFormAuthenticator, MessageBusInterface $commandBus)
    {
        $this->adminCreatorStrategy = $adminCreatorStrategy;
        $this->authenticatorHandler = $authenticatorHandler;
        $this->loginFormAuthenticator = $loginFormAuthenticator;
        $this->commandBus = $commandBus;
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


            $driver = $this->command($formData['email'], $form);

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

    private function command($email, FormInterface $form): mixed
    {
        $message = new CreateDriverCommand($email, $form->get('plainPassword')->getData(),
            $this->adminCreatorStrategy);
        $envelope = $this->commandBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        
        return $handledStamp->getResult();
    }
}
