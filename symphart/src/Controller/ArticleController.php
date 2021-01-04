<?php


namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route ("/article/{id}", name="show_article", requirements={"id" = "\d+"})
     * @param $id
     * @return Response
     */
    public function showArticle(int $id): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route ("/article/new", name="new_article")
     * @param Request $request
     * @return Response
     */
    public function newArticle(Request $request): Response
    {
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('body', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'create',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $newArticleId = $this->save($form->getData());
            if ($newArticleId > 0) {
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('articles/new.html.twig', [
           'form' => $form->createView(),
        ]);
    }


    /**
     * @param Article $newArticle
     * @return Integer
     */
    public function save(Article $newArticle): Int
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newArticle);
        $entityManager->flush();

        return $newArticle->getId();
    }

    /**
     * @Route ("/article/delete/{id}", methods={"DELETE"}, name="delete_article", requirements={"id" = "\d+"})
     * @param int $id
     */
    public function delete(int $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}