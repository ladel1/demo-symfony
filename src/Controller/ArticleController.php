<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class,$comment);        
        $article=$articleRepository->findOneByUrl($url);
        return $this->render('article/index.html.twig',[
            "article"=>$article,
            "form"=>$formComment->createView()
        ]);
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
        if($this->isGranted("ROLE_USER")){
            // instancier la classe Article
            $article = new Article();
            // Créer le formulaire et le relier avec l'objet article
            $form = $this->createForm(ArticleType::class,$article);
            // Récuperer les données de formulaire
            $form->handleRequest($request);
            // verifier si formulaire est valide et envoyé
            if($form->isSubmitted() && $form->isValid()){     
                // persister les dossier et genéer insert       
                $this->entityManager->persist($article);
                // appliquer les changement de l'objet article dans la BDD
                $this->entityManager->flush();
                // Rediréger vers la page article
                $this->addFlash("message","Article ajouté!");
                return $this->redirectToRoute("app_article_list");
            }
            return $this->render('article/add.html.twig',[
                "formArticle"=>$form->createView()
            ]);
        }else{
            return $this->redirectToRoute("app_login");
        }
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
