<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


#[Route('/article')]
class ArticleController extends AbstractController
{

    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $articles = $entityManager
            ->getRepository(Article::class)
            ->findBy([], []);
        // premier param de findby () ça va être ce que je select, deuxieme param c'est l'ordre et le troisieme la limite


        $pagination = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1), // numéro de la page par défaut ca va etre 1
            5  // nombre d'articles par page
        );
        
        $pagination->setCustomParameters(['addClass' => 'new']);

        return $this->render('article/index.html.twig', [
            'pagination' => $pagination //c'est pagination qui contient mes articles maintenant
        ]);
    }


    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,TranslatorInterface $translator): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('Article bien ajouté !'));

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{idArticle}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{idArticle}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager,TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('Article bien modifié !'));

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{idArticle}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager,TranslatorInterface $translator): Response
    {
        try {
            if ($this->isCsrfTokenValid('delete' . $article->getIdArticle(), $request->request->get('_token'))) {
                $entityManager->remove($article);
                $entityManager->flush();
                $this->addFlash('success', $translator->trans('Article bien supprimé !'));
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Impossible de supprimer l\'article. Assurez-vous qu\'il n\'est pas lié à d\'autres données.');
        }
    
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/articles/langue/anglais', name: 'app_articles_langue_anglais')]
    public function articlesAnglais(Request $request): Response
    {
        $request->getSession()->set('_locale', 'en');
        $request->setLocale($request->getSession()->get('_locale'));

        return $this->redirectToRoute('app_articles_index');
    }

    #[Route('/articles/langue/francais', name: 'app_articles_langue_francais')]
    public function articlesFrancais(Request $request): Response
    {
        $request->getSession()->set('_locale', 'fr');
        $request->setLocale($request->getSession()->get('_locale'));

        return $this->redirectToRoute('app_articles_index');
    }
}
