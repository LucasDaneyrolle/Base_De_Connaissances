<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\ResponseTopic;
use App\Entity\Topic;
use App\Form\TopicResponseType;
use App\Form\TopicType;
use App\Repository\CategoryRepository;
use App\Repository\ResponseCategoryRepository;
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

            $objTopic->setState(false);
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
    public function show(TopicRepository $topicRepository, CategoryRepository $categoryRepository): Response
    {
        $topics = $topicRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('blog/show.html.twig', [
            'topics' => $topics,
            'categories' => $categories
        ]);
    }

    #[Route('/show/', name: 'app_blog_show_cat')]
    public function showCategories(?Category $category, CategoryRepository $categoryRepository): Response
    {
        if(!$category) {
            $this->redirectToRoute("app_accueil");
        }

        $categories = $categoryRepository->findAll();

        return $this->render('blog/showCategory.html.twig', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    #[Route('/show/{id}', name: 'app_blog_show_id')]
    public function showForm(TopicRepository $topicRepository, $id, Request $request, EntityManagerInterface $entityManager, ?Category $category): Response
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
            'category' => $category,
        ]);
    }

    #[Route('/show/comment/{id}/delete', name: 'app_blog_show_com_del', methods: ['GET', 'DELETE'])]
    public function deleteCom($id, ResponseCategoryRepository $responseCategoryRepository, EntityManagerInterface $em): Response
    {
        $response = $responseCategoryRepository->find($id);
        $em->remove($response);
        $em->flush();

        return $this->redirectToRoute('app_blog_show');
    }

    #[NoReturn] #[Route('/edit/{id}/', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic, TopicRepository $repoTopic, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, int $id): Response
    {
        $user = $this->getUser();
        $topic->categoriesTopic = $repoTopic->findAllCategory($id);

        if (($user->getId()) === ($topic->getUser()->getId())) {
            $formPage = $this->createForm(TopicType::class, $topic)->handleRequest($request);

            if ($formPage->isSubmitted() && $formPage->isValid()) {
                $idCategories = [];
                $checkedCategories = $formPage->get("topicCategory")->getData();

                foreach ($categoryRepository->findAll() as $category) {
                    $idCategories[$category->getId()] = $category->getLibelle();
                }

                foreach ($idCategories as $id => $value) {
                    $objCategory = new Category();
                    $category = $objCategory->fetchByID($id, $categoryRepository);

                    if (array_search($id, $checkedCategories) !== false)
                        $topic->addTopicCategory($category);
                    else
                        $topic->removeTopicCategory($category);

                }

                $topic->setState(false);

                $entityManager->persist($topic);
                $entityManager->flush();

                return $this->redirectToRoute('app_blog_show', [], Response::HTTP_SEE_OTHER);
            }
        }
        else {
            return $this->redirectToRoute('app_blog_show');
        }

        return $this->renderForm('blog/edit.html.twig', [
            'topicFormulaire' => $formPage,
        ]);
    }

    #[NoReturn] #[Route('/cate/{cateValue}/', name: 'app_blog_cate', methods: ['GET', 'POST'])]
    public function cate(TopicRepository $repoTopic, CategoryRepository $categoryRepository, string $cateValue) {
        $topics     = $repoTopic->findByCate($cateValue);
        $categories = $categoryRepository->findAll();

        return $this->render('blog/show.html.twig', [
            'topics'     => $topics,
            'categories' => $categories
        ]);
    }
}
