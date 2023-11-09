<?php

namespace App\Repository;

use App\Entity\Magasinier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Magasinier>
 *
 * @method Magasinier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Magasinier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Magasinier[]    findAll()
 * @method Magasinier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagasinierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Magasinier::class);
    }

//    /**
//     * @return Magasinier[] Returns an array of Magasinier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Magasinier
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
