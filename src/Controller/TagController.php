<?php


namespace App\Controller ;


use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    //DECLARATION DU ROUTING dans l annotation pour lier une URL à une portion de code grâce au composant Routing
    /**
     * @Route("/tags" , name="tagList")
     */
    //DECLARATION DE LA METHODE AVEC EN PARAMETRE : instanciation de la classe repository
    public function tagList (TagRepository $tagRepository)
    {
        // declaration de la variable pour stocker les resultats de la requete
       $tags = $tagRepository->findAll();
       //renvoi de la reponse html soit la forme d une vue liée à au fichier twig correspondant
        return $this->render('tagList.html.twig', ['tags' => $tags]);

    }
    //MISE EN PLACE D UNE WILDCARD pour ajouter dans l url ici la variable Id afin d atteindre un élément specifique
    /**
     * @Route ("/tags/{id}" , name="tagShow")
     */
    //DECLARATION DE LA METHODE AVEC EN PARAMETRE :
    // instanciation de la classe repository via autowire avec comme premier parametre la variable visée par la wildcard
    public function tagShow($id , TagRepository $tagRepository)
    {
        // declaration de la variable pour stocker les resultats de la requete
        $tag = $tagRepository->find($id);
        //message d erreur si le tag mis en url n existe pas
        if (is_null($tag)){
            throw new NotFoundHttpException();
        }
        //renvoi de la reponse html soit la forme d une vue liée à au fichier twig correspondant
        return $this->render('tagShow.html.twig' , ['tag' => $tag]);
    }
}