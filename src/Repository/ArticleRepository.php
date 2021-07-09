<?php

namespace App\Repository;

use App\Entity\article;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //ajout d une methode pour effectuer la recherche dans les articles en fonction du terme renseigné
    public function searchByTerm($term)
    {
        //Déclaration de l'objet QueryBuilder (objet générateur de requete SQL)
        $queryBuilder = $this->createQueryBuilder('article');

        //Instanciation et utilisation via Doctrine de QueryBuilder pour faire la requete de recherche
        $query = $queryBuilder
            ->select('article')
            //jointure entre tables(a placer sous le select en général)
            ->leftJoin('article.category', 'category')
            ->leftJoin('article.tag' , 'tag')
            //mise en place des filtres de recherche
            ->where('article.content LIKE :term')
            ->orWhere('article.title LIKE :term')
            ->orWhere('category.title LIKE :term')
            ->orWhere('tag.title LIKE :term')
            //parametre de securite pour eviter qu un utisateur utilise la recherche pour des requetes SQL
            ->setParameter('term', '%'.$term.'%')
            //generation de la requete SQL
            ->getQuery();

        //Demande de renvoi de reponse à la requete
        return $query->getResult();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
