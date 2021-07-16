<?php


namespace App\Controller\Admin ;


use App\Entity\Tag;
use App\Form\CategoryType;
use App\Form\TagType;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminTagController extends AbstractController
{
    //CREATION DE LA METHODE INSERT
    /**
     * @Route("/admin/tag/insert", name="adminTagInsert")
     */
    public function insertTag(EntityManagerInterface $entityManager , Request $request)
    {
        //Création d une variable qui instancie l entité Tag
        //pour créer un nouveau tag dans la bdd (ei un nouvel enregistrement dans la table visée)
        $tag = new Tag();

        //Récupération du gabarit formulaire pour le stocker dans une variable
        //en parametre : instanciation du gabarit et nom de l entité visée ou de celle à créer
        $tagForm = $this->createForm(TagType::class, $tag);

        //mise en relation du formulaire avec les données envoyées en Post
        $tagForm->handleRequest($request);

        //mise en place d une condition pour vérifier la validité du formulaire  au niveau de la saisie des champs
        //et le bon envoi des données en post
        // si les deux conditions sont ok alors l enregistrement en bdd s effectue
        if($tagForm->isSubmitted() && $tagForm->isValid() )
        {
            $entityManager->persist($tag);
            $entityManager->flush();
            //si ok on renvoi sur la page list pour voir le nouvel article
            return $this->redirectToRoute('adminTagList');
        }

        //renvoi du formulaire sur une page vue si le formulaire n est pas validé
        return $this->render('Admin/AdminTagInsert.html.twig',['tagForm'=> $tagForm ->createView()] );

        //ETAPE EN HARDCODE
            //Utilisation des setters de l entité Tag
            //pour permettre l ajout de valeurs dans chaque colonne (propriété de l entité)
           //$tag->setTitle('Faits divers');
            //$tag->setColor('pink ');
            //Pré sauvegarde des entités crées via la methode "persist"
             //$entityManager->persist($tag);
            //insertion en bdd des entités crées en bdd via la methode "flush"
            //$entityManager->flush();
            //renvoi vers la page list en fin d action
            //return $this->redirectToRoute('adminTagList') ;
    }

    //DECLARATION DE LA METHODE UPDATE
    /**
     * @Route("/admin/tag/update/{id}" , name="adminTagUpdate")
     */
    public function updateTag($id , TagRepository $tagRepository, EntityManagerInterface $entityManager, Request $request)
    {
        //recupération du tag à modifier en fonction de son id defini dans la wildcard
        $tag = $tagRepository->find($id);
        //Récupération du gabarit formulaire pour le stocker dans une variable
        //en parametre : instanciation du gabarit et nom de l entité visée ou de celle à créer
        $tagForm = $this->createForm(TagType::class, $tag);

        //mise en relation du formulaire avec les données envoyées en Post
        $tagForm->handleRequest($request);

        //mise en place d une condition pour vérifier la validité du formulaire  au niveau de la saisie des champs
        //et le bon envoi des données en post
        // si les deux conditions sont ok alors l enregistrement en bdd s effectue
        if($tagForm->isSubmitted() && $tagForm->isValid() )
        {
            $entityManager->persist($tag);
            $entityManager->flush();
            //si ok on renvoi sur la page list pour voir le nouvel article
            return $this->redirectToRoute('adminTagList');
        }

        //renvoi du formulaire sur une page vue si le formulaire n est pas validé
        return $this->render('Admin/AdminTagInsert.html.twig',['tagForm'=> $tagForm ->createView()] );

        //methode HardCOdé
        //ajout de la nouvelle valeur a modifier
        //$tag->setColor('aqua');
        //pré sauvegarde et envoi en bdd
        //$entityManager->persist($tag);
        //$entityManager->flush();
        //redirection sur une page définie en fin d'éxécution
       // return $this->redirectToRoute('adminTagList') ;
    }

    //CREATION DE LA METHODE DELETE
    /**
     * @Route ("/admin/tag/delete/{id}" , name="adminTagDelete")
     */
    public function deleteTag($id, TagRepository $tagRepository, EntityManagerInterface $entityManager)
    {
        //recupération de le tag a supprimer en fonction de son id defini dans la wildcard
        $tag = $tagRepository->find($id);

        //mise en place des managers de gestion des entités
        //pour supprimer l element selectionné avec son id
        $entityManager->remove($tag);
        $entityManager->flush();

        //redirection sur une page définie en fin d'éxécution
        return $this->redirectToRoute('adminTagList');
    }

    //DECLARATION DU ROUTING dans l annotation pour lier une URL à une portion de code grâce au composant Routing
    /**
     * @Route("/admin/tags" , name="adminTagList")
     */
    //DECLARATION DE LA METHODE AVEC EN PARAMETRE : instanciation de la classe repository
    public function tagList (TagRepository $tagRepository)
    {
        // declaration de la variable pour stocker les resultats de la requete
       $tags = $tagRepository->findAll();

       //renvoi de la reponse html sous la forme d une vue liée au fichier twig correspondant
        return $this->render('Admin/AdminTagList.html.twig', ['tags' => $tags]);

    }
    //MISE EN PLACE D UNE WILDCARD pour ajouter dans l url ici la variable Id afin d atteindre un élément specifique
    /**
     * @Route ("/admin/tags/{id}" , name="adminTagShow")
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
        return $this->render('Admin/AdminTagShow.html.twig' , ['tag' => $tag]);
    }
}