<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog/contact", name="blog-contact")
     */
    public function contact(): Response
    {
        return $this->render('blog/contact.html.twig', [
            // 'controller_name' => 'BlogController',
        ]);
    }

 /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $articleRepo): Response
    {
        $articles = $articleRepo->findAll();
        // dd($articles); //dump and die
        return $this->render('blog/index.html.twig', [
            'articles'=> $articles
        ]);
    }

    /**
     * @Route("/article/{id}", name="art-detail")
     */
    public function artDetail(Article $article): Response
    {
        // $articles = $articleRepo->findAll();
        // dd($article); //dump and die
        return $this->render('blog/artDetail.html.twig', [
            'article'=> $article
        ]);
    }
}