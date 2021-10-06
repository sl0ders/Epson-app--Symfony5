<?php

namespace App\Repository;

use App\Entity\Printer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Printer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Printer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Printer[]    findAll()
 * @method Printer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrinterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Printer::class);
    }

    /**
     * this function retrieve the print owned by a company
     * @param $company
     * @return QueryBuilder
     */
    public function getPrinterOfCompanyUser($company): QueryBuilder
    {
        return $this->createQueryBuilder("p")
            ->andWhere("p.company = :company")
            ->setParameter("company", $company);
    }

    public function getAllPrinter()
    {
        $this->createQueryBuilder("p");
    }
}
