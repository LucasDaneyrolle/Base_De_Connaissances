<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Form;
use App\Form\FicheType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class FormController extends AbstractController
{
    #[Route('/form', name: 'app_form')]

    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objFiche = new Form();
        $objForm = $this->createForm(FicheType::class, $objFiche);

        // dd($repoCategory->findAll());

        $objForm->handleRequest($request);

        if ($objForm->isSubmitted() && $objForm->isValid()) {
            $objUser = $this->getUser();
            $tbaCategories = $objForm->get("categorie")->getData();
            $dthNow = new DateTimeImmutable('now');

            $objFiche->setState(2);
            $objFiche->setCreatedAt($dthNow);
            $objFiche->setUser($objUser);

            // foreach($tbaCategories as $intClef => $intID) {
            //     $objFiche->addCategoryForm($intID);
            // }

            $entityManager->persist($objFiche);
            $entityManager->flush();

            return $this->redirectToRoute('app_form');
        }

        return $this->render('form/index.html.twig', [
            'ficheFormulaire' => $objForm->createView(),
        ]);
    }
}
