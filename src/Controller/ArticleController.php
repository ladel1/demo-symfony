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
        // méthode 1
        $repo = $this->getDoctrine()->getRepository(Article::class);
        dump($repo->find(1));
        // méthode 2 inject ArticleRepo
        $articles=$articleRepository->findOneByTitle("Formation Python");
        dd($articles);
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
