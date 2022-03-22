<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/new",name="art-new")
     * @Route("/article/edit/{id}",name="art-edit")
     */
    public function addOrUpdateArticle(
        Article $article = null,
        Request $req,
        EntityManagerInterface $em
    ) {
        if (!$article) {
            $article = new Article();
        }

        $formArticle = $this->createForm(ArticleType::class, $article);

        $formArticle->handleRequest($req);
        // dump($req);
        // dump($article);
        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('art-detail', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('blog/artForm.html.twig', [
            'formArticle' => $formArticle->createView(),
            'mode' => $article->getId() != null,
        ]);
    }
    /**
     * @Route("/article/delete/{id}",name="art-delete")
     */
    public function deleteArticle(Article $article, EntityManagerInterface $em)
    {
        if ($article) {
            $em->remove($article);
            $em->flush();
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/article/{id}", name="art-detail")
     */
    public function artDetail(Article $article): Response
    {
        // $articles = $articleRepo->findAll();
        // dd($article); //dump and die
        return $this->render('blog/artDetail.html.twig', [
            'article' => $article,
            'comments' => $article->getComments(),
        ]);
    }
    /**
     * @Route("/comment/add/{id}",name="art-addCom")
     */
    public function addComment(
        Article $article,
        EntityManagerInterface $em,
        Request $req
    ) {
        $comment = new Comment();
        if ($article) {
            //Init articleId du commentaire
            $comment->setRelation($article);
        }
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($req);
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('art-detail', [
                'id' => $article->getId(),
            ]);
        }
        return $this->render('blog/commentForm.html.twig', [
            'formComment' => $formComment->createView(),
        ]);
    }
}
