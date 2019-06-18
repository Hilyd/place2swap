<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Entity\Article;
use App\Utilities\Elasticsearch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $elastic;

    public function __construct(Elasticsearch $elastic)
    {
        $this->elastic = $elastic;
    }

    /**
     * Route pour poster un article
     *
     * @Route("/post", name="post")
     */
    public function postArticle(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newArticle = $form->getData();


            $file = $article->getImage();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('images_directory'), $fileName);
            } catch (FileException $e) {
                return new Response('error');
            }

            $article->setImage($fileName);
            $date = new \DateTime();
            $dateF = $date->format('Y-m-d');

            $article->setDate($dateF);

            if ($newArticle instanceof Article) {

                $elastic = $this->elastic->index($newArticle);
                $article->setId($elastic['_id']);

            }
            return new Response('<META http-equiv="refresh" content="2; URL='. $this->generateUrl('getArticle', ['id' => $article->getId()]) .'">Votre article a bien été posté');

        }
        return $this->render('article/postArticle.html.twig', [
            'article_form' => $form->createView()
        ]);
    }

    /**
     * @return string
     */
    public function generateUniqueFileName()
    {
        return md5(uniqid());
    }


    /**
     * Route pour afficher un article
     *
     * @Route("/article/{id}", name="getArticle")
     */
    public function getArticle($id) {
        $article = $this->elastic->get('_id', $id);
        if ($article instanceof Article) {
            return $this->render('article/getArticle.html.twig',
            ['article' => $article]);
        }
        return new Response('error');
    }
    /**
     * Route pour afficher la liste des articles
     *
     * @Route("/list", name="list")
     */
    public function getListArticles() {
        $list = $this->elastic->search();

        if (count($list)) {
            return $this->render('article/list.html.twig',
                ['list' => $list]);
        }
        return new Response('error');
    }



}
