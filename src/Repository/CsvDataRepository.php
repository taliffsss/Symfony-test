<?php

namespace App\Repository;

use App\Entity\CsvData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CsvData>
 *
 * @method CsvData|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsvData|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsvData[]    findAll()
 * @method CsvData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsvDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsvData::class);
    }

//    /**
//     * @return CsvData[] Returns an array of CsvData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CsvData
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
