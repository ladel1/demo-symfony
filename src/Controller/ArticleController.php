<?php

namespace App\Controller;

use App\Entity\Article;
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
    public function list(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }


    /**
     * @Route("/article/{url}", name="app_article_show")
     */
    public function show($url): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    
    /**
     * @Route("/articles/ajouter", name="app_article_add")
     */
    public function add(): Response
    {
        $article = new Article();

        $article->setTitle("Formation Symfony version 6.0.1")
                ->setContent("balblablabla")
                ->setDateCreated(new \DateTime("now"))
                ->setDateUpdated(new \DateTime("now")) 
                ->setAuthor("Adel Latibi")
                ->setUrl("formation-symfony");   

        $this->entityManager->persist($article);
        $this->entityManager->flush();
        
        dd("Article ajouté");
    }    
}
