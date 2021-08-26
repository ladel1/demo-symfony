<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\isEmpty;

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
    public function add(Request $request): Response
    {
        if(!empty($request->request->get("action"))){
            if(
                !empty($request->request->get("title")) &&
                !empty($request->request->get("content")) &&
                !empty($request->request->get("author")) 
                ){
                    $article = new Article();
                    $title =$request->request->get("title");
                    $url = str_replace(" ","-",strtolower($title));
                    $article->setTitle($title)
                            ->setContent($request->request->get("content"))
                            ->setDateCreated(new \DateTime("now"))
                            ->setDateUpdated(new \DateTime("now")) 
                            ->setAuthor($request->request->get("author"))
                            ->setUrl($url);                     
                    $this->entityManager->persist($article);
                    $this->entityManager->flush();

                return $this->redirectToRoute("app_article_show",["url"=>$url]);

            }else{

                dump("pas ok");

            }
        }



        return $this->render('article/add.html.twig');
    }  
    
    /**
     * @Route("/articles/modifier/{id}", name="app_article_update")
     */
    public function update(ArticleRepository $articleRepository,$id): Response
    {
        // recup de l'article
        $article = $articleRepository->find($id);  
        // modif de titre
        $article->setTitle("Formation Machine Learning");
        // flush -> appliquer tt les change sur l'objet 
        $this->entityManager->flush();        
        dd("Article modifié");
    }     
}
