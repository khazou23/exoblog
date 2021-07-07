<?php


namespace App\Controller ;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles" , name="articlelist")
     */
//utilisation de l autowire pour instancier la classe repository pour pouvoir faire mes requête sql
    public function articleList(ArticleRepository $ArticleRepository)
    {
        //instruction de requete
        $articles = $ArticleRepository->findAll();
        //renvoi de la reponse
        return $this->render('articleList.html.twig' , [
            'articles'=>$articles ]);
    }

//declaration de la methode pour selectionner une seul article en fonction de l'id dans l url
//utilisation d'une wildcard avec id pour la recherche via url
    /**
     * @Route("/articles/{id}" , name="articleShow")
     */
    public function articleShow($id , ArticleRepository $ArticleRepository)
    {
        $article=$ArticleRepository->find($id);

        return $this->render('articleShow.html.twig' ,['article'=>$article]);
    }
}



?>