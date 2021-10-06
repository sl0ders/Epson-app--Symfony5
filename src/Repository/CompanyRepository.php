<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * This function is the filter of company enable or not
     * @return QueryBuilder
     */
    public function getCompanyEnabled(): QueryBuilder
    {
        return $this->createQueryBuilder("c")
            ->andWhere('c.isEnabled = true');
    }

    /**
     * this function retrieve all company by order ascending with all printer users cartridge and consumable linked
     * @return int|mixed|string
     */
    public function findAllOrderByConsumableDate()
    {
        $qb = $this->createQueryBuilder("company")
            ->leftJoin("company.printers", "printers")->addSelect("printers")
            ->leftJoin("company.users", "users")->addSelect("users")
            ->leftJoin('printers.cartridge', "cartridges")->addSelect("cartridges")
            ->leftJoin("printers.consumables", "consumables")->addSelect("consumables")
            ->orderBy("consumables.date_update", "ASC");
        return $qb->getQuery()->getResult();
    }

}
