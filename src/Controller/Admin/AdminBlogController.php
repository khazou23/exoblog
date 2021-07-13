<?php


namespace App\Controller\Admin ;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AdminBlogController extends AbstractController
{
    //CREATION DE LA METHODE INSERT
    /**
     * @Route("/admin/categories/insert", name="adminCategoryInsert")
     */
    public function insertCategory(EntityManagerInterface $entityManager )
    {
        //Création d une variable qui instancie l entité Categorie
        //pour créer une nouvelle categorie dans la bdd (ei un nouvel enregistrement dans la table visée)
        $category = new Category();

        //Utilisation des setters de l entité Categorie
        //pour permettre l ajout de valeurs dans chaque colonne (propriété de l entité)
       $category->setTitle('Historique');
       $category->setContent('Avec stephane Bern en force ');
       $category->setPublished(true);

        //Pré sauvegarde des entités crées via la methode "persist"
        $entityManager->persist($category);
        //insertion en bdd des entités crées en bdd via la methode "flush"
        $entityManager->flush();

        //redirection sur une page définie en fin d'éxécution
        return $this->redirectToRoute('adminCategoriesList') ;
    }

    //DECLARATION DE LA METHODE UPDATE
    /**
     * @Route("/admin/category/update/{id}" , name="adminCategoryUpdate")
     */
    public function updateCategory($id , CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        //recupération de la categorie à modifier en fonction de son id defini dans la wildcard
        $category = $categoryRepository->find($id);

        //ajout de la nouvelle valeur a modifier
        $category->setTitle('titre modifié');

        //pré sauvegarde et envoi en bdd
        $entityManager->persist($category);
        $entityManager->flush();

        //redirection sur une page définie en fin d'éxécution
        return $this->redirectToRoute('adminCategoriesList') ;
    }

    //CREATION DE LA METHODE DELETE
    /**
     * @Route ("/admin/category/delete/{id}" , name="adminCategoryDelete")
     */
    public function categoryDelete($id, categoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        //requete SQL pour recupération de la categorie à modifier en fonction de son id defini dans la wildcard
        $category = $categoryRepository->find($id);

        //mise en place des managers de gestion des entités
        //pour supprimer l element selectionné avec son id
        $entityManager->remove($category);
        $entityManager->flush();

        //redirection sur une page définie en fin d'éxécution
        return $this->redirectToRoute('adminCategoriesList');
    }


    //CREATION DE PAGE LISTE DE TOUTES LES CATEGORIES
    //anotation pour le routing de la page
    /**
     * @Route("/admin/categories", name="adminCategoriesList")
     */
    public function categories(CategoryRepository $CategoryRepository)
    {
        //requete SQL pour recupérer tous les éléments de category
        $categories = $CategoryRepository->findAll();

        //renvoi de la reponse avec la mise en place des connexions twig
        return $this->render('Admin/AdminCategoriesList.html.twig' ,['categories' => $categories]);
    }

    //CREATION DE LA PAGE VUE POUR CATEGORY
    //anotation pour le routing de la page
    /**
     * @Route("/admin/category/{id}", name="adminCategory")
     */
    public function category($id ,CategoryRepository $CategoryRepository )
    {
        //requete SQL pour recupération de la categorie à modifier en fonction de son id defini dans la wildcard
        $categorie= $CategoryRepository->find($id);

        //message d erreur si le tag mis en url n existe pas
        if (is_null($categorie)){
            throw new NotFoundHttpException();
        }

        //renvoi de la reponse avec la mise en place des connexions twig
        return $this->render('Admin/AdminCategory.html.twig', ['categorie'=>$categorie]);
    }

}
