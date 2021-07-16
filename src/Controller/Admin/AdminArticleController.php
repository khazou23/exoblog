<?php


namespace App\Controller\Admin ;

use App\Entity\article;
use App\Entity\Tag;
use App\Form\ArticleType;
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

        //renvoi de la reponse html sous la forme d une vue liée au fichier twig correspondant
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
        TagRepository $tagRepository ,
        Request $request)
    {
        //Création d une variable qui instancie l entité Article
        //pour créer un nouvel article dans la bdd (ei un nouvel enregistrement dans la table visée
        $article = new Article();

        //Récupération du gabarit formulaire pour le stocker dans une variable
        //en parametre : instanciation du gabarit et nom de l entité visée ou de celle à créer
        $articleForm = $this->createForm(ArticleType::class, $article);

        //mise en relation du formulaire avec les données envoyées en Post
        $articleForm->handleRequest($request);

        //mise en place d une condition pour vérifier la validité du formulaire  au niveau de la saisie des champs
        //et le bon envoi des données en post
        // si les deux conditions sont ok alors l enregistrement en bdd s effectue
        if($articleForm->isSubmitted() && $articleForm->isValid() )
        {
            $entityManager->persist($article);
            $entityManager->flush();

            //ajout d un message flash
            $this ->addFlash(
                'success',
                'L\'article ' . $article -> getTitle() . ' a été créé '
            );

            //si ok on renvoi sur la page list pour voir le nouvel article
            return $this->redirectToRoute('adminArticleList');
        }

        //renvoi du formulaire sur une page vue si le formulaire n est pas validé
        return $this->render('Admin/AdminArticleInsert.html.twig',['articleForm'=> $articleForm ->createView()] );

//ancien insert sans formulaire
        //Utilisations des setters de l entité Article
        //pour permettre l ajout de valeurs dans chaque colonne (propriété de l entité)
        //$article->setTitle('Je suis une creation du controleur');
        //$article->setContent('on est lundi matin , la nuit a été toute pourrie , je hais le lundi vive garfield...envie de meurtre');
        //$article->setIsPublished(true);
        //$article->setCreateAt(new\DateTime('NOW'));

        //Recuperation de l id de categorie qu on veut ajouter à notre enregistrement
        //$category = $categoryRepository->find(2);
        //association de categorie avec id 2 avec l entité que l on crée
        //$category->setCategory($category);

        //ajout d un nouveau tag
        //$tag= new Tag();
        //$tag->setTitle('info');
        //$tag->setColor('blue');

        //persist a mettre pour chaque element et flush à la fin une seule fois
        //$entityManager->persist($tag);
        //$article->setTag($tag);

        //Pré sauvegarde des entités crées via la methode "persist"
        //$entityManager->persist($article);
        //insertion en bdd des entités crées en bdd via la methode "flush"
        //$entityManager->flush();

        //redirection sur une page définie en fin d'éxécution
        //return $this->redirectToRoute('adminArticleList') ;
    }

    //DECLARATION DE LA METHODE UPDATE
    /**
     * @Route("/admin/articles/update/{id}" , name="adminArticleUpdate")
     */
    public function updateArticle($id , ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request)
    {
        //recupération de l article à modifier en fonction de son id defini dans la wildcard
        $article = $articleRepository->find($id);
        //Récupération du gabarit formulaire pour le stocker dans une variable
        //en parametre : instanciation du gabarit et nom de l entité visée ou de celle à créer
        $articleForm = $this->createForm(ArticleType::class, $article);

        //mise en relation du formulaire avec les données envoyées en Post
        $articleForm->handleRequest($request);

        //mise en place d une condition pour vérifier la validité du formulaire  au niveau de la saisie des champs
        //et le bon envoi des données en post
        // si les deux conditions sont ok alors l enregistrement en bdd s effectue
        if($articleForm->isSubmitted() && $articleForm->isValid() )
        {
            $entityManager->persist($article);
            $entityManager->flush();

            //ajout d un message flash
            $this ->addFlash(
                'success',
                'L\'article ' . $article -> getTitle() . ' a été modifié '
            );

            //si ok on renvoi sur la page list pour voir le nouvel article
            return $this->redirectToRoute('adminArticleList');
        }

        //renvoi du formulaire sur une page vue si le formulaire n est pas validé
        return $this->render('Admin/AdminArticleInsert.html.twig',['articleForm'=> $articleForm ->createView()] );

    //methode hardcodé
        //ajout de la nouvelle valeur a modifier
        //$article->setTitle('jeudi modifié');
        //pré sauvegarde et envoi en bdd
        //$entityManager->persist($article);
        //$entityManager->flush();
        //redirection sur une page définie en fin d'éxécution
        //return $this->redirectToRoute('adminArticleList') ;
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

        //ajout d un message flash
        $this ->addFlash(
            'success',
            'L\'article ' . $article -> getTitle() . ' a été supprimé '
        );

        //redirection sur une page définie en fin d'éxécution
        return $this->redirectToRoute('adminArticleList') ;
    }

    //declaration de la methode pour selectionner une seul article en fonction de l'id dans l url
    //utilisation d'une wildcard avec id pour la recherche via url
    /**
     * @Route("admin/articles/{id}" , name="adminArticleShow")
     */
    public function articleShow($id , ArticleRepository $ArticleRepository)
    {
        // declaration de la variable pour stocker les resultats de la requete
        $article=$ArticleRepository->find($id);
        //message d erreur si le tag mis en url n existe pas
        if (is_null($article)){
            throw new NotFoundHttpException();
        }

        //renvoi de la reponse html sous la forme d une vue liée au fichier twig correspondant
        return $this->render('Admin/adminArticleShow.html.twig' ,['article'=>$article]);
    }

}



?>