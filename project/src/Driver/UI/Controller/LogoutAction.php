<?php

namespace App\Driver\UI\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Routing\Annotation\Route;

class LogoutAction extends AbstractController
{
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - 
        it will be intercepted by the logout key on your firewall.');
    }
}