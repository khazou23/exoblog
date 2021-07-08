<?php


namespace App\Controller ;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
//creation de la page qui affiche toutes les categories
    //anotation pour le routing de la page
    /**
     * @Route("/categories", name="categories")
     */
    public function categories(CategoryRepository $CategoryRepository)
    {
        //instructions de requete
        $categories = $CategoryRepository->findAll();
        //renvoi de la reponse avec la mise en place des connexions twig
        return $this->render('categoriesList.html.twig' ,['categories' => $categories]);
    }

//creation de la page qui affiche toutes les categories
    //anotation pour le routing de la page
    /**
     * @Route("/category/{id}", name="category")
     */
    public function category($id ,CategoryRepository $CategoryRepository )
    {
        $categorie= $CategoryRepository->find($id);
//message d erreur si le tag mis en url n existe pas
        if (is_null($categorie)){
            throw new NotFoundHttpException();
        }
        return $this->render('category.html.twig', ['categorie'=>$categorie]);
    }

}
