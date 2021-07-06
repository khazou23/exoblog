<?php


namespace App\Controller ;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
//declaratiion de la variable
    private $categories =
        [
        1 => [
           "title" => "Politique",
            "content" => "Tous les articles liés à Jean Lassalle",
            "id" => 1,
            "published" => true,
            ],
        2 => [
            "title" => "Economie",
            "content" => "Les meilleurs tuyaux pour avoir DU FRIC",
            "id" => 2,
            "published" => true
            ],
        3 => [
            "title" => "Securité",
            "content" => "Attention les étrangers sont très méchants",
            "id" => 3,
            "published" => false
            ],
        4 => [
             "title" => "Ecologie",
             "content" => "Hummer <3",
             "id" => 4,
             "published" => true
             ]
    ];

//creation de la page qui affiche toutes les categories
    //anotation pour le routing de la page
    /**
     * @Route("/list", name="list")
     */
    public function list()
    {
        //Mise en place des connexions twig
        return $this->render('list.html.twig' ,['categories' => $this->categories]);
    }

//creation de la page qui affiche toutes les categories
    //anotation pour le routing de la page
    /**
     * @Route("/category/{id}", name="category")
     */
    public function category($id)
    {
        return $this->render('category.html.twig', ['categorie'=>$this->categories[$id]]);
    }

}
