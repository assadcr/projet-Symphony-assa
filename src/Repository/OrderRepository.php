<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Order::class);
    }

    public function findOrderWithRelations($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->select('o', 'c', 'oi', 'p')
            ->leftJoin('o.customer', 'c')
            ->leftJoin('o.orderItems', 'oi')
            ->leftJoin('oi.product', 'p')
            ->where('o.id = :id')
            ->setParameter(':id', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function paginateOrdersByCustomerId(int $page, int $customerId): PaginationInterface
    {
        return $this->paginator->paginate($this->createQueryBuilder('o')
            ->select('o', 'c')
            ->leftJoin('o.customer', 'c')
            ->where('c.id = :customerId')
            ->setParameter(':customerId', $customerId), $page, 3);
    }

    public function findOrdersByCustomerId(int $customerId): ?array
    {
        return $this->createQueryBuilder('o')
            ->select('o', 'c')
            ->leftJoin('o.customer', 'c')
            ->where('c.id = :customerId')
            ->setParameter(':customerId', $customerId)
            ->getQuery()
            ->getResult();
    }
}

