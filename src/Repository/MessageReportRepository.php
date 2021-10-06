<?php

namespace App\Repository;

use App\Entity\MessageReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageReport[]    findAll()
 * @method MessageReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageReport::class);
    }
}
