<?php

namespace App\Controller;

use App\Entity\CommentForm;
use App\Entity\ResponseTopic;
use App\Form\CommentType;
use App\Form\TopicResponseType;
use App\Repository\CommentFormRepository;
use App\Repository\FormRepository;
use DateTimeImmutable;
use App\Entity\Form;
use App\Entity\Category;
use App\Form\FicheType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
    public function show(FormRepository $formRepository, CategoryRepository $categoryRepository): Response
    {
        $fiches = $formRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('fiche/show.html.twig', [
            'forms' => $fiches,
            'categories' => $categories
        ]);
    }

    #[Route('/show/{libelle}', name: 'app_form_show_cat')]
    public function showCategories(?Category $category, CategoryRepository $categoryRepository): Response
    {
        if(!$category) {
            $this->redirectToRoute("app_accueil");
        }

        $categories = $categoryRepository->findAll();

        return $this->render('fiche/showCategory.html.twig', [
            'category' => $category,
            'categories' => $categories
        ]);
    }


    #[Route('/show/{libelle}/{id}', name: 'app_form_show_id')]
    public function showForm(FormRepository $formRepository, $id, Request $request, EntityManagerInterface $entityManager,?Category $category): Response
    {
        $fiche = $formRepository->find($id);

        $comment = new CommentForm();
        $formPage = $this->createForm(CommentType::class, $comment); // Création du formulaire
        $formPage->handleRequest($request);

        if ($formPage->isSubmitted() && $formPage->isValid()) {
            $comment
                ->setCreatedAt(new \DateTimeImmutable())
                ->setForm($fiche)
                ->setUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute("app_form_show", ['fiche_id' => $fiche->getId()]); // Redirection vers l'accueil
        }

        return $this->render('fiche/showForm.html.twig', [
            'form' => $fiche,
            'ficheForm' => $formPage->createView(),
            'category' => $category,
        ]);
    }

    #[Route('/show/comment/{id}/delete', name: 'app_form_show_com_del', methods: ['GET', 'DELETE'])]
    public function deleteCom($id, CommentFormRepository $commentFormRepository, EntityManagerInterface $em): Response
    {
        $comment = $commentFormRepository->find($id);
        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('app_form_show');
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

        return $this->renderForm('fiche/edit.html.twig', [
            'ficheFormulaire' => $formPage,
        ]);
    }
}
