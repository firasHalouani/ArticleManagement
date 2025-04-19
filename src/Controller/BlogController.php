<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig');
    }

    #[Route('/blog', name: 'app_blog')]
    public function index(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/blog/{id}', name: 'blog_show', requirements: ['id' => '\d+'])]
    public function show($id, ArticleRepository $repo): Response
    {
        $article = $repo->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé.');
        }

        return $this->render('blog/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/blog/new', name: 'new_form')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $article->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(\App\Form\ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article créé avec succès !');
            return $this->redirectToRoute('app_blog');
        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
        ]);
    }


    #[Route('/blog/edit/{id}', name: 'edit_article')]
    public function edit(Request $request, EntityManagerInterface $entityManager, $id, ArticleRepository $repo): Response
    {
        $article = $repo->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé.');
        }

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('image', TextType::class)
            ->add('content', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Mettre à jour'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_blog');
        }

        return $this->render('blog/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/blog/delete/{id}', name: 'delete_article')]
    public function delete(EntityManagerInterface $entityManager, $id, ArticleRepository $repo): Response
    {
        $article = $repo->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé.');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_blog');
    }
}
