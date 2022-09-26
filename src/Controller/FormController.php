<?php

namespace App\Controller;

use App\Entity\Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FormController extends AbstractController
{
    #[Route('/form', name: 'app_form')]
    public function index(): Response
    {
        return $this->render('registration/register.html.twig', [
            'toto' => "titi",
        ]);
    }
}
