<?php


namespace App\Controller\Admin ;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminUserController extends AbstractController
{
    //DECLARATION DU ROUTING dans l annotation pour lier une URL à une portion de code grâce au composant Routing
    /**
     * @Route("/admin/userList" , name="adminUserList")
     */
    //DECLARATION DE LA METHODE AVEC EN PARAMETRE : instanciation de la classe repository
    public function userList(UserRepository $userRepository)
    {
        // declaration de la variable pour stocker les resultats de la requete
        $users = $userRepository->findAll();

        //renvoi de la reponse html sous la forme d une vue liée au fichier twig correspondant
        return $this->render('Admin/AdminUserList.html.twig', ['users' => $users]);

    }

    //CREATION DE LA METHODE INSERT
    /**
     * @Route("/admin/user/insert", name="adminUserInsert")
     */
    public function insertUser(EntityManagerInterface $entityManager , Request $request, UserPasswordHasherInterface $UserPasswordHasher)
    {
        //Création d une variable qui instancie l entité User
        //pour créer un nouveau user dans la bdd (ei un nouvel enregistrement dans la table visée)
        $user = new User();

        //Récupération du gabarit formulaire pour le stocker dans une variable
        //en parametre : instanciation du gabarit et nom de l entité visée ou de celle à créer
        $userForm = $this->createForm(UserType::class, $user);

        //mise en relation du formulaire avec les données envoyées en Post
        $userForm->handleRequest($request);

        //mise en place d une condition pour vérifier la validité du formulaire  au niveau de la saisie des champs
        //et le bon envoi des données en post
        // si les deux conditions sont ok alors l enregistrement en bdd s effectue
        if($userForm->isSubmitted() && $userForm->isValid() )
        {
            //Insertion du role avec la methode set des roles admin dans un array
            //$user->setRoles(["ROLE_ADMIN"]);

            //encodage du mot de passe avec une fonction de hachage cryptographique
            //pour transformer le mot de passe en texte brut d'origine en une valeur différente non devinable qu'il est compliqué d'inverser.
            $plainPassword = $userForm->get('password')->getData();
            $hashedPassword = $UserPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            //ajout d un message flash
            $this ->addFlash(
                'success',
                'L\'utilisateur ' . $user -> getEmail() . ' a été créé '
            );

            //si ok on renvoi sur la page list pour voir le nouvel enregistrement
            return $this->redirectToRoute('adminUserList');
        }

        //renvoi du formulaire sur une page vue si le formulaire n est pas validé
        return $this->render('Admin/AdminUserInsert.html.twig',['userForm'=> $userForm ->createView()] );

    }

    //DECLARATION DE LA METHODE UPDATE
    /**
     * @Route("/admin/user/update/{id}" , name="adminUserUpdate")
     */
    public function updateUser($id , UserRepository $userRepository, EntityManagerInterface $entityManager, Request $request)
    {
        //recupération du tag à modifier en fonction de son id defini dans la wildcard
        $user = $userRepository->find($id);

        //Récupération du gabarit formulaire pour le stocker dans une variable
        //en parametre : instanciation du gabarit et nom de l entité visée ou de celle à créer
        $userForm = $this->createForm(UserType::class, $user);

        //mise en relation du formulaire avec les données envoyées en Post
        $userForm->handleRequest($request);

        //mise en place d une condition pour vérifier la validité du formulaire  au niveau de la saisie des champs
        //et le bon envoi des données en post
        // si les deux conditions sont ok alors l enregistrement en bdd s effectue
        if($userForm->isSubmitted() && $userForm->isValid() )
        {
            $entityManager->persist($user);
            $entityManager->flush();

            //ajout d un message flash
            $this ->addFlash(
                'success',
                'L\'utilisateur ' . $user -> getEmail() . ' a été modifié '
            );

            //si ok on renvoi sur la page list pour voir le nouvel article
            return $this->redirectToRoute('adminUserList');
        }

        //renvoi du formulaire sur une page vue si le formulaire n est pas validé
        return $this->render('Admin/AdminUserList.html.twig',['userForm'=> $userForm ->createView()] );
    }

    //CREATION DE LA METHODE DELETE
    /**
     * @Route ("/admin/user/delete/{id}" , name="adminUserDelete")
     */
    public function deleteUser($id, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        //recupération de le tag a supprimer en fonction de son id defini dans la wildcard
        $user = $userRepository->find($id);

        //mise en place des managers de gestion des entités
        //pour supprimer l element selectionné avec son id
        $entityManager->remove($user);
        $entityManager->flush();

        //ajout d un message flash
        $this ->addFlash(
            'success',
            'L\'utilisateur' . $user -> getEmail() . ' a été supprimé'
        );

        //redirection sur une page définie en fin d'éxécution
        return $this->redirectToRoute('adminUserList');
    }


    //MISE EN PLACE D UNE WILDCARD pour ajouter dans l url ici la variable Id afin d atteindre un élément specifique
    /**
     * @Route ("/admin/user/{id}" , name="adminUserShow")
     */
    //DECLARATION DE LA METHODE AVEC EN PARAMETRE :
    // instanciation de la classe repository via autowire avec comme premier parametre la variable visée par la wildcard
    public function userShow($id , UserRepository $userRepository)
    {
        // declaration de la variable pour stocker les resultats de la requete
        $user = $userRepository->find($id);

        //message d erreur si le tag mis en url n existe pas
        if (is_null($user)){
            throw new NotFoundHttpException();
        }

        //renvoi de la reponse html soit la forme d une vue liée à au fichier twig correspondant
        return $this->render('Admin/AdminUserShow.html.twig' , ['user' => $user]);
    }
}