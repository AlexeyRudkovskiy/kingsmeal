<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getAllCount()
    {
        $query = $this->createQueryBuilder('o');

        return intval($query->select($query->expr()->count('o.id'))
            ->getQuery()
            ->getSingleResult()[1]);
    }

    public function findWithStatus(PaginatorInterface $paginator, Request $request, string $status = null)
    {
        $query = $this->createQueryBuilder('o');

        if ($status === null) {
            $query = $query
                ->getQuery()
                ->getResult();
        } else {
            $query = $query
                ->where('o.status = ?1')
                ->setParameter(1, $status)
                ->getQuery()
                ->getResult();
        }

        return $paginator->paginate($query, $request->query->getInt('page', 1), 1);
    }

    public function getUnprocessedCount()
    {
        $query = $this->createQueryBuilder('o');

        return intval($query->select($query->expr()->count('o.id'))
            ->where('o.status = ?1')
            ->setParameter(1, 'unprocessed')
            ->getQuery()
            ->getSingleResult()[1]);
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
