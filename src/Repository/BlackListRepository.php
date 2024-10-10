<?php

namespace App\Repository;

use App\Entity\BlackList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlackList>
 *
 * @method BlackList|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlackList|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlackList[]    findAll()
 * @method BlackList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlackListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlackList::class);
    }

    public function save(BlackList $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BlackList $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BlackList[] Returns an array of BlackList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BlackList
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findOneByUser($value): ?BlackList
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.userId = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return BlackList[] Returns an array of BlackList objects
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
