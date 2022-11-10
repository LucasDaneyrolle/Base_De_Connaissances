<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfilType;
use App\Form\EditPasswordType;
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
            'editProfilForm' => $form->createView()
        ]);
    }

    #[Route('/profil/password', name: 'app_profil_pass')]
    public function editPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userpasshasher)
    {
        $form = $this->createForm(EditPasswordType::class, $this->getUser());
        $form -> handleRequest($request);
        $user = $this->getUser();
        var_dump($user->getPassword());

        if($form->isSubmitted() && $form->isValid()){
            if($form->get('newPassword')->getData() == $form->get('confirmPassword')->getData()) {
                $user->setPassword(
                    $userpasshasher->hashPassword(
                        $user,
                        $form->get('newPassword')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('message', 'Le mot de passe a bien été modifié');
                return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
            }
            else {
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('profil/editpass.html.twig', [
            'newPasswordForm' => $form->createView()
        ]);
    }
}
