<?php

namespace App\Controller;

use App\Repository\FormRepository;
use DateTimeImmutable;
use App\Entity\Form;
use App\Entity\Category;
use App\Form\FicheType;
use DateTime;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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

        return $this->render('form/index.html.twig', [
            'ficheFormulaire' => $objForm->createView(),
        ]);
    }

    #[Route('/show', name: 'app_form_show')]
    public function show(FormRepository $formRepository): Response
    {
        $fiches = $formRepository->findAll();

        return $this->render('form/show.html.twig', [
            'forms' => $fiches,
        ]);
    }

    #[Route('/show/{id}', name: 'app_form_show_id')]
    public function showForm(FormRepository $formRepository, $id): Response
    {
        $fiche = $formRepository->find($id);

        return $this->render('form/showForm.html.twig', [
            'form' => $fiche,
        ]);
    }

    #[NoReturn] #[Route('/edit/{id}/', name: 'app_form_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Form $fiche, EntityManagerInterface $entityManager, FormRepository $ficheRepo, CategoryRepository $categoryRepository, int $id): Response
    {
        $fiche->categoriesForm = $ficheRepo->findAllCategory($id);

        $formPage = $this->createForm(FicheType::class, $fiche)->handleRequest($request);

        if ($formPage->isSubmitted() && $formPage->isValid()) {
            $idCategories      = [];
            $checkedCategories = $formPage->get("categorie")->getData();

            foreach ($categoryRepository->findAll() as $category) {
                $idCategories[$category->getId()] = $category->getLibelle();
            }

            foreach ($idCategories as $id => $value) {
                $objCategory = new Category();
                $category    = $objCategory->fetchByID($id, $categoryRepository);

                if (array_search($id, $checkedCategories) !== false)
                    $fiche->addCategoryForm($category);
                else
                    $fiche->removeCategoryForm($category);

            }

            $entityManager->persist($fiche);
            $entityManager->flush();

            //return $this->redirectToRoute('app_form_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('form/edit.html.twig', [
            'ficheFormulaire' => $formPage,
        ]);
    }
}
