<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\BlocRepository;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'index')]
    public function listArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('index/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'article')]
    public function article(int $id, ArticleRepository $articleRepository, BlocRepository $blocRepository, ThemeRepository $themeRepository): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }

        $blocs = $blocRepository->findByArticle($article);
        $activeTheme = $themeRepository->findOneBy(['isActive' => true]);

        return $this->render('index/article.html.twig', [
            'article' => $article,
            'blocs' => $blocs,
            'activeTheme' => $activeTheme,
        ]);
    }
}
