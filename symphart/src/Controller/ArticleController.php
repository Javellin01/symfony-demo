<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends BaseController
{
    /**
     * @Route ("/", name="home")
     * @return Response
     */
    public function index() {

        $articles = ['Article 1', 'Article 2', 'Article 3'];

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}