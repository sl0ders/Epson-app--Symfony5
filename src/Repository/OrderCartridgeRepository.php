<?php

namespace App\Repository;

use App\Entity\Cartridge;
use App\Entity\OrderCartridge;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderCartridge|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderCartridge|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderCartridge[]    findAll()
 * @method OrderCartridge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderCartridgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderCartridge::class);
    }

    /**
     * this function is here for create a new order cartridge code
     * Classic dependencies
     * @return QueryBuilder
     */
    private function queryOrderCartridge(): QueryBuilder
    {
        $qb = $this->createQueryBuilder("order_cartridge");
        return $qb;
    }
    /**
     * this function is here for create a new order cartridge code
     * @param QueryBuilder $qb
     * @param $p_prefix
     * @return QueryBuilder
     */
    private function filterNumber(QueryBuilder $qb, $p_prefix): QueryBuilder
    {
        return $qb->andWhere($qb->expr()->like('order_cartridge.orderCode', ':prefix'))
            ->setParameter(':prefix', '%' . $p_prefix . '%');
    }

        /**
         * this function is here for create a new order cartridge code
         * Find max num used with specific prefix
         * Use on NumberSaleOrderGenerator
         *
         * @param $p_prefix
         * @return mixed
         */
        public function findMaxNumberWithPrefix($p_prefix)
    {
        $qb = $this->queryOrderCartridge();
        $qb = $this->filterNumber($qb, $p_prefix);

        $qb->select($qb->expr()->max('order_cartridge.orderCode'));

        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return $e;
        }
    }
}
