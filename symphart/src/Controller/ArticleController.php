<?php


namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends BaseController
{
    /**
     * @Route ("/", name="home")
     * @return Response
     */
    public function index()
    {

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route ("/article/{id}", name="show_article")
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route ("/article/save")
     * @return Response
     */
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setTitle('Article 1');
        $article->setBody('This is body for article 1');

        $entityManager->persist($article);

        $entityManager->flush();

        return new Response('Article successfully saved. Article Id is' . $article->getId());
    }
}