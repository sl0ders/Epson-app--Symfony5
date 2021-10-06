<?php

namespace App\Repository;

use App\Entity\Cartridge;
use App\Entity\Printer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cartridge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cartridge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cartridge[]    findAll()
 * @method Cartridge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartridgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cartridge::class);
    }

    /**
     * this function retrieves the last 4 ink cartridges from a printer
     * @param $printer
     * @return int|mixed|string
     */
    public function findLastCartridgeByPrint(Printer $printer)
    {
        $qb = $this->createQueryBuilder('cartridge')
            ->leftJoin('cartridge.printer', "p")->addSelect("p")
            ->leftJoin("p.consumables", "c")->addSelect("c")
            ->where('p = :printer')
            ->setParameter(':printer', $printer)
            ->setMaxResults(4)
            ->orderBy('c.date_update', 'DESC');
        return $qb->getQuery()->getResult();
    }
}
