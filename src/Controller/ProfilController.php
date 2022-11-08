<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig');
    }

    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function editProfil(Request $request)
    {
        $form = $this->createForm(EditProfilType::class, $this->getUser());
        $form->handleRequest($request);
        console.log("test");

        return $this->render('profil/editprofil.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
