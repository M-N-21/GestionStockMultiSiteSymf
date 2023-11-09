<?php

namespace App\Repository;

use App\Entity\TransfertStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TransfertStock>
 *
 * @method TransfertStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransfertStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransfertStock[]    findAll()
 * @method TransfertStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransfertStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransfertStock::class);
    }

//    /**
//     * @return TransfertStock[] Returns an array of TransfertStock objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TransfertStock
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
