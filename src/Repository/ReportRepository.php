<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    /**
     * this function is here for create a new report code
     * Classic dependencies
     * @return QueryBuilder
     */
    private function queryOrderCartridge(): QueryBuilder
    {
        $qb = $this->createQueryBuilder("report");
        return $qb;
    }
    /**
     * this function is here for create a new report code
     * @param QueryBuilder $qb
     * @param $p_prefix
     * @return QueryBuilder
     */
    private function filterNumber(QueryBuilder $qb, $p_prefix): QueryBuilder
    {
        return $qb->andWhere($qb->expr()->like('report.reportCode', ':prefix'))
            ->setParameter(':prefix', '%' . $p_prefix . '%');
    }

    /**
     * this function is here for create a new report code
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

        $qb->select($qb->expr()->max('report.reportCode'));

        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return $e;
        }
    }
}
