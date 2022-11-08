<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\ResponseTopic;
use App\Entity\Topic;
use App\Form\TopicResponseType;
use App\Form\TopicType;
use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/add', name: 'app_blog_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $objTopic = new Topic();
        $objForm = $this->createForm(TopicType::class, $objTopic);

        $objForm->handleRequest($request);

        if ($objForm->isSubmitted() && $objForm->isValid()) {
            $objUser       = $this->getUser();
            $dthNow        = new DateTimeImmutable('now');
            $tbaCategories = $objForm->get("topicCategory")->getData();

            $objTopic->setState(2);
            $objTopic->setCreatedAt($dthNow);
            $objTopic->setUser($objUser);

            foreach($tbaCategories as $intClef => $intID) {
                $objCategory  = new Category();

                $objParam = $objCategory->fetchByID($intID, $categoryRepository);

                $objTopic->addTopicCategory($objParam);
            }

            $entityManager->persist($objTopic);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_add');
        }

        return $this->render('blog/index.html.twig', [
            'topicFormulaire' => $objForm->createView(),
        ]);
    }

    #[Route('/show', name: 'app_blog_show')]
    public function show(TopicRepository $topicRepository): Response
    {
        $topics = $topicRepository->findAll();

        return $this->render('blog/show.html.twig', [
            'topics' => $topics,
        ]);
    }

    #[Route('/show/{id}', name: 'app_blog_show_id')]
    public function showForm(TopicRepository $topicRepository, $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $topic = $topicRepository->find($id);

        $response = new ResponseTopic();
        $formPage = $this->createForm(TopicResponseType::class, $response); // Création du formulaire
        $formPage->handleRequest($request);

        if ($formPage->isSubmitted() && $formPage->isValid()) {
            $response
                ->setCreatedAt(new \DateTimeImmutable())
                ->setTopic($topic)
                ->setUser($this->getUser());

            $entityManager->persist($response);
            $entityManager->flush();

            return $this->redirectToRoute("app_blog_show", ['topic_id' => $topic->getId()]); // Redirection vers l'accueil
        }

        return $this->render('blog/showForm.html.twig', [
            'topic' => $topic,
            'responseForm' => $formPage->createView(),
        ]);
    }

    #[NoReturn] #[Route('/edit/{id}/', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, $id): Response
    {
         $category = $categoryRepository->findAll();
//        $fichesCategory = $formCategoryRepository->findAll();

        $formPage = $this->createForm(TopicType::class, $topic)->handleRequest($request);

        if ($formPage->isSubmitted() && $formPage->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog/edit.html.twig', [
            'topicFormulaire' => $formPage,
        ]);
    }


}
