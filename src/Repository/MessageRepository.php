<?php

namespace App\Repository;


use Symfony\Component\Validator\Constraints\DateTime;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function save(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function saveCustom(string $text, User $sender, User $receiver): void
    {

        $message = new Message();

        $message->setText($text);
        $message->setDatetime(new \DateTime());
        $message->setSenderId($sender);
        $message->setReceiverId($receiver);
        $message->setFileway(null);

        $flush = false;
        $this->getEntityManager()->persist($message);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }




     /**
     * @return Message[] Returns an array of Message objects
     */
    public function findBySR($value1, $value2): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.senderId = :val1')
            ->setParameter('val1', $value1)
            ->andWhere('m.receiverId = :val2')
            ->setParameter('val2', $value2)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByRS($value1, $value2): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.receiverId = :val1')
            ->setParameter('val1', $value1)
            ->andWhere('m.senderId = :val2')
            ->setParameter('val2', $value2)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Message[] Returns an array of Message objects
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

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
