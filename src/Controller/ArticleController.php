<?php


namespace App\Controller ;

use App\Entity\article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    /**
    * @Route("/articles/insert" , name="articleInsert")
    */
    public function insertArticle(EntityManagerInterface $entityManager)
    {
        //Création d une variable qui instancie l entité Article
        //pour créer un nouvel article dans la bdd (ei un nouvel enregistrement dans la table visée
        $article = new Article();

        //Utislisations des setters de l entité Article
        //pour permettre l ajout de valeurs dans chaque colonne (propriété de l entité)
        $article->setTitle('Je suis une creation du controleur');
        $article->setContent('on est lundi matin , la nuit a été toute pourrie , je hais le lundi vive garfield...envie d meurtre');
        $article->setIsPublished(true);
        $article->setCreateAt(new\DateTime('NOW'));

        //Pré sauvegarde des entités crées via la methode "persist"
        $entityManager->persist($article);

        //insertion en bdd des entités crées en bdd via la methode "flush"
        $entityManager->flush();

        dump('ok'); die;
    }

    //declaration de la methode pour selectionner une seul article en fonction de l'id dans l url
    //utilisation d'une wildcard avec id pour la recherche via url
    /**
     * @Route("/articles/{id}" , name="articleShow")
     */
    public function articleShow($id , ArticleRepository $ArticleRepository)
    {
        $article=$ArticleRepository->find($id);
        //message d erreur si le tag mis en url n existe pas
        if (is_null($article)){
            throw new NotFoundHttpException();
        }

        return $this->render('articleShow.html.twig' ,['article'=>$article]);
    }

    //DECLARATION METHODE pour afficher les resultats de la requete de recherche du repository sur la page web
    /**
     * @Route("/search" , name="search")
     */
    //ajout en parametre des instanciations des repository et de la requete
    public function search(ArticleRepository $articleRepository , Request $request)
    {
        //declaration de la variable qui stocke le mot saisie dans le formulaire
        $term = $request->query->get('q');

        //declaration variable : stocker les éléments retournés via repository en fonction de $term
        $articles = $articleRepository->searchByTerm($term);

        //Methode pour renvoi de la reponse de la requete repository
        return $this->render('articleSearch.html.twig' , [
            'articles' => $articles,
            'term' => $term
        ]);

    }


}



?>