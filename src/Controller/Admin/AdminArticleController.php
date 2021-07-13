<?php


namespace App\Controller\Admin ;

use App\Entity\article;
use App\Entity\Tag;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleController extends AbstractController
{
    /**
     * @Route("/admin/articles" , name="adminArticleList")
     */
    //utilisation de l autowire pour instancier la classe repository pour pouvoir faire mes requête sql
    public function articleList(ArticleRepository $ArticleRepository)
    {
        //instruction de requete
        $articles = $ArticleRepository->findAll();
        //renvoi de la reponse
        return $this->render('Admin/AdminArticleList.html.twig' , [
            'articles'=>$articles
        ]);
    }
    /**
    * @Route("/admin/articles/insert" , name="adminArticleInsert")
    */
    public function insertArticle(
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository )
    {
        //Création d une variable qui instancie l entité Article
        //pour créer un nouvel article dans la bdd (ei un nouvel enregistrement dans la table visée
        $article = new Article();

        //Utislisations des setters de l entité Article
        //pour permettre l ajout de valeurs dans chaque colonne (propriété de l entité)
        $article->setTitle('Je suis une creation du controleur');
        $article->setContent('on est lundi matin , la nuit a été toute pourrie , je hais le lundi vive garfield...envie de meurtre');
        $article->setIsPublished(true);
        $article->setCreateAt(new\DateTime('NOW'));

        //Recuperation de l id de categorie qu on veut ajouter à notre enregistrement
        $category = $categoryRepository->find(2);
        //association de categorie avec id 2 avec l entité que l on crée
        $category->setCategory($category);

        //ajout d un nouveau tag
        $tag= new Tag();
        $tag->setTitle('info');
        $tag->setColor('blue');

        //persist a mettre pour chaque element et flush à la fin une seule fois
        $entityManager->persist($tag);
        $article->setTag($tag);

        //Pré sauvegarde des entités crées via la methode "persist"
        $entityManager->persist($article);
        //insertion en bdd des entités crées en bdd via la methode "flush"
        $entityManager->flush();

        return $this->redirectToRoute('adminArticleList') ;
    }

    //DECLARATION DE LA METHODE UPDATE
    /**
     * @Route("/admin/articles/update/{id}" , name="adminArticleUpdate")
     */
    public function updateArticle($id , ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        //recupération de l article à modifier en fonction de son id defini dans la wildcard
        $article = $articleRepository->find($id);
        //ajout de la nouvelle valeur a modifier
        $article->setTitle('jeudi modifié');

        //pré sauvegarde et envoi en bdd
        $entityManager->persist($article);
        $entityManager->flush();

        return $this->redirectToRoute('adminArticleList') ;
    }

    //DECLARATION DE LA METHODE DELETE
    /**
     * @Route("admin/articles/delete/{id}" , name="adminArticleDelete")
     */
    public function deleteArticle($id , ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        //recupération de l article à supprimer en fonction de son id defini dans la wildcard
        $article = $articleRepository->find($id);

        //mise en place des managers de gestion des entités
        //pour supprimer l element selectionné avec son id
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('adminArticleList') ;
    }

    //declaration de la methode pour selectionner une seul article en fonction de l'id dans l url
    //utilisation d'une wildcard avec id pour la recherche via url
    /**
     * @Route("admin/articles/{id}" , name="adminArticleShow")
     */
    public function articleShow($id , ArticleRepository $ArticleRepository)
    {
        $article=$ArticleRepository->find($id);
        //message d erreur si le tag mis en url n existe pas
        if (is_null($article)){
            throw new NotFoundHttpException();
        }

        return $this->render('Admin/adminArticleShow.html.twig' ,['article'=>$article]);
    }

}



?>