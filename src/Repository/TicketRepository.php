<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ticket>
 *
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

//    /**
//     * @return Ticket[] Returns an array of Ticket objects
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

//    public function findOneBySomeField($value): ?Ticket
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

        public function findLowDataTickets()
        {
            $entityManager = $this->getEntityManager();

            $sql = "SELECT technology.name AS technology, CONCAT(user.firstname, ' ' , user.lastname) AS name, ticket.subject, status.name FROM ticket 
            LEFT JOIN technology ON technology_id = technology.id
            LEFT JOIN user ON owner_id = user.id
			LEFT JOIN status ON status_id = status.id
WHERE status.name = 'En attente'";
    
            $stmt = $entityManager->getConnection()->prepare($sql);
            // if($excludePostId !== null){
            //     $stmt->bindValue('excludePostId', $excludePostId);
            // }
    
            $result = $stmt->executeQuery();
    
            // $stmt->
    
            return $result->fetchAllAssociative();   
        }
}
