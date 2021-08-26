<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/articles", name="app_article_list")
     */
    public function list(ArticleRepository $articleRepository): Response
    {
        $articles=$articleRepository->findAll();
        return $this->render('article/list.html.twig', compact('articles'));
    }


    /**
     * @Route("/article/{url}", name="app_article_show")
     */
    public function show(ArticleRepository $articleRepository,$url): Response
    {
        $article=$articleRepository->findOneByUrl($url);
        return $this->render('article/index.html.twig', compact('article'));
    }

    /**
     * @Route("/article/supprimer/{id}", name="app_article_remove")
     */
    public function remove(ArticleRepository $articleRepository,$id): Response
    {
        $article=$articleRepository->find($id);
        $this->entityManager->remove($article);
        $this->entityManager->flush();
        return $this->redirectToRoute("app_article_list");
    }    
    
    /**
     * @Route("/articles/ajouter", name="app_article_add")
     */
    public function add(): Response
    {
        $article = new Article();

        $article->setTitle("Formation Python")
                ->setContent("Formation PythonFormation PythonFormation Python")
                ->setDateCreated(new \DateTime("now"))
                ->setDateUpdated(new \DateTime("now")) 
                ->setAuthor("Adel Latibi")
                ->setUrl("formation-python");   

        $this->entityManager->persist($article);
        $this->entityManager->flush();
        
        dd("Article ajouté");
    }    
}
