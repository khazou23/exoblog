<?php


namespace App\Controller ;


use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
//declaration du routing
    /**
     * @Route("/tags" , name="tagList")
     */
    public function tagList (TagRepository $tagRepository)
    {
    // declaration de la variable pour stocker les resultats de la requete
       $tags = $tagRepository->findAll();
    //renvoi de la reponse
        return $this->render('tagList.html.twig', ['tags' => $tags]);

    }
//mise en place d une wildcard
    /**
     * @Route ("/tags/{id}" , name="tagShow")
     */
    public function tagShow($id , TagRepository $tagRepository)
    {
        $tag = $tagRepository->find($id);
//message d erreur si le tag mis en url n existe pas
        if (is_null($tag)){
            throw new NotFoundHttpException();
        }
        return $this->render('tagShow.html.twig' , ['tag' => $tag]);
    }
}