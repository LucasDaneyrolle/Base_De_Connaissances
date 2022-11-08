<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;


class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig');
    }

    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function editProfil(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(EditProfilType::class, $this->getUser());
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profil/editprofil.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/profil/password', name: 'app_profil_pass')]
    public function editPassword(Request $request, UserPasswordHasherInterface $userpasshasher)
    {
        if($request->isMethod('POST')){
            if($request->$request->get('pass') == $request->$request->get('pass2')) {

            }
            else {
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }
        
        return $this->render('profil/editpass.html.twig');
    }
}
