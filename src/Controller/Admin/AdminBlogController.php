<?php


namespace App\Controller\Admin ;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class AdminBlogController extends AbstractController
{
//creation de la page qui affiche toutes les categories
    //anotation pour le routing de la page
    /**
     * @Route("/admin/categories", name="adminCategoriesList")
     */
    public function categories(CategoryRepository $CategoryRepository)
    {
        //instructions de requete
        $categories = $CategoryRepository->findAll();
        //renvoi de la reponse avec la mise en place des connexions twig
        return $this->render('Admin/AdminCategoriesList.html.twig' ,['categories' => $categories]);
    }

//creation de la page qui affiche toutes les categories
    //anotation pour le routing de la page
    /**
     * @Route("/admin/category/{id}", name="adminCategory")
     */
    public function category($id ,CategoryRepository $CategoryRepository )
    {
        $categorie= $CategoryRepository->find($id);
//message d erreur si le tag mis en url n existe pas
        if (is_null($categorie)){
            throw new NotFoundHttpException();
        }
        return $this->render('Admin/AdminCategory.html.twig', ['categorie'=>$categorie]);
    }

}
