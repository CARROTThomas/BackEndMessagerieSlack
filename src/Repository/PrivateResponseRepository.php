<?php

namespace App\Repository;

use App\Entity\PrivateResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrivateResponse>
 *
 * @method PrivateResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrivateResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrivateResponse[]    findAll()
 * @method PrivateResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrivateResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrivateResponse::class);
    }

//    /**
//     * @return PrivateResponse[] Returns an array of PrivateResponse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PrivateResponse
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
