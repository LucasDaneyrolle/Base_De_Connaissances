<?php

namespace App\Controller;

use App\Repository\FormRepository;
use DateTimeImmutable;
use App\Entity\Form;
use App\Entity\Category;
use App\Form\FicheType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/form')]
class FormController extends AbstractController
{
    #[Route('/add', name: 'app_form_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $objFiche = new Form();
        $objForm = $this->createForm(FicheType::class, $objFiche);

        $objForm->handleRequest($request);

        if ($objForm->isSubmitted() && $objForm->isValid()) {
            $objUser       = $this->getUser();
            $dthNow        = new DateTimeImmutable('now');
            $tbaCategories = $objForm->get("categorie")->getData();

            $objFiche->setState(2);
            $objFiche->setCreatedAt($dthNow);
            $objFiche->setUser($objUser);

            foreach($tbaCategories as $intClef => $intID) {
                $objCategory     = new Category();

                $objParam = $objCategory->fetchByID($intID, $categoryRepository);

                $objFiche->addCategoryForm($objParam);
            }

            $entityManager->persist($objFiche);
            $entityManager->flush();

            return $this->redirectToRoute('app_form_add');
        }

        return $this->render('fiche/index.html.twig', [
            'ficheFormulaire' => $objForm->createView(),
        ]);
    }

    #[Route('/show', name: 'app_form_show')]
    public function show(FormRepository $formRepository): Response
    {
        $fiches = $formRepository->findAll();

        return $this->render('fiche/show.html.twig', [
            'forms' => $fiches,
        ]);
    }

    #[Route('/show/{id}', name: 'app_form_show_id')]
    public function showForm(FormRepository $formRepository, $id): Response
    {
        $fiche = $formRepository->find($id);

        return $this->render('fiche/showForm.html.twig', [
            'form' => $fiche,
        ]);
    }

    #[NoReturn] #[Route('/edit/{id}/', name: 'app_form_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Form $fiche, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->findAll();
//        $fichesCategory = $formCategoryRepository->findAll();

        $formPage = $this->createForm(FicheType::class, $fiche)->handleRequest($request);

        if ($formPage->isSubmitted() && $formPage->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche/edit.html.twig', [
            'ficheFormulaire' => $formPage,
        ]);
    }
}
