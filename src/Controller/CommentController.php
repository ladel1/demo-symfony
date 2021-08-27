<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index(Request $request,EntityManagerInterface $em,ArticleRepository $repo): Response
    {
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class,$comment);
        // recup data
        $formComment->handleRequest($request); 
        if($formComment->isSubmitted()){
            // persist 
            $article = $repo->findOneBy(["url"=>$request->request->get("urlArticle")]);
            $comment->setDateCreated(new \DateTime("now"));
            $comment->setArticle($article);
            $em->persist($comment);
            $em->flush();
            // redirect
        }
        return $this->redirectToRoute("app_article_show",["url"=>$request->request->get("urlArticle")]);      
    }
}
