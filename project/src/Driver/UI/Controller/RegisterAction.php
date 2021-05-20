<?php

namespace App\Driver\UI\Controller;


use App\Driver\Application\Message\Command\CreateDriverCommand;
use App\Driver\Application\Security\LoginFormAuthenticator;
use App\Driver\Domain\Model\Driver;
use App\Driver\Infrastructure\Form\RegistrationFormType;
use App\Driver\Infrastructure\Service\DriverCreatorStrategy\NormalDriverCreatorStrategy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegisterAction extends AbstractController
{
    private NormalDriverCreatorStrategy $normalDriverCreatorStrategy;
    private GuardAuthenticatorHandler $authenticatorHandler;
    private LoginFormAuthenticator $loginFormAuthenticator;
    private MessageBusInterface $commandBus;

    public function __construct(NormalDriverCreatorStrategy $normalDriverCreatorStrategy,
                                GuardAuthenticatorHandler $authenticatorHandler,
                                LoginFormAuthenticator $loginFormAuthenticator, MessageBusInterface $commandBus)
    {
        $this->normalDriverCreatorStrategy = $normalDriverCreatorStrategy;
        $this->authenticatorHandler = $authenticatorHandler;
        $this->loginFormAuthenticator = $loginFormAuthenticator;
        $this->commandBus = $commandBus;
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

    /**
     * @param $email
     * @param FormInterface $form
     * @return mixed
     */
    private function command($email, FormInterface $form): mixed
    {
        $message = new CreateDriverCommand($email, $form->get('plainPassword')->getData(),
            $this->normalDriverCreatorStrategy);
        $envelope = $this->commandBus->dispatch($message);
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        return $handledStamp->getResult();
    }
}