<?php

namespace App\Controller;

use App\Repository\FormRepository;
use DateTimeImmutable;
use App\Entity\Form;
use App\Entity\Category;
use App\Entity\FormCategory;
use App\Form\FicheType;
use DateTime;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        // dd($repoCategory->findAll());

        $objForm->handleRequest($request);

        if ($objForm->isSubmitted() && $objForm->isValid()) {
            $objUser = $this->getUser();
            $dthNow = new DateTimeImmutable('now');
            $tbaCategories = $objForm->get("categorie")->getData();

            $objFiche->setState(2);
            $objFiche->setCreatedAt($dthNow);
            $objFiche->setUser($objUser);

            $entityManager->persist($objFiche);
            $entityManager->flush();

            foreach($tbaCategories as $intClef => $intID) {
                $objCategory = new Category();
                $objFormCategory = new FormCategory();

                $objParam = $objCategory->fetchByID($intID, $categoryRepository);

                $objFormCategory->setCategory($objParam);
                $objFormCategory->setFiche($objFiche);

                $entityManager->persist($objFormCategory);
                $entityManager->flush();
            }

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
}
