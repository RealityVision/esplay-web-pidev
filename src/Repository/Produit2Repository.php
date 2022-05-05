<?php

namespace App\Repository;

use App\Entity\Produit2;
use App\Form\Produit2Type;
use App\Form\ProduitSearchType;
use App\Entity\ProduitSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit2|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit2|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit2[]    findAll()
 * @method Produit2[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Produit2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit2::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Produit2 $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Produit2 $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Query
     */
    public function findAllVisibleQuery(ProduitSearch $search): Query
    {
        $query = $this->findVisibleQuery();
        if($search->getMaxPrice()) {
            $query =$query
                -> where('p.prix <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }
        if($search->getMinStock()) {
            $query =$query
                -> where('p.stockProduit >= :minstock')
                ->setParameter('stockProduit', $search->getMinStock());
        }
             return $query ->getquery();


    }

    // /**
    //  * @return Produit2[] Returns an array of Produit2 objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit2
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
