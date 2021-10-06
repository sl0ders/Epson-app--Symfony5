<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\ConsumableDelta;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsumableDelta|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsumableDelta|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsumableDelta[]    findAll()
 * @method ConsumableDelta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsumableDeltaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsumableDelta::class);
    }

    /**
     * This function retrieve the last deltas for an print
     * @param $print
     * @return int|mixed|string
     */
    public function findLastConsumableDeltaByPrint($print)
    {
        $qb = $this->createQueryBuilder("c")
            ->where("c.printer = :printer")
            ->orderBy("c.updateAt", "DESC")
            ->setMaxResults(1)
            ->setParameter(":printer", $print);
        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
            return 'Not enough information';
        }
    }

    /*****************************************************************************************************
     *                                                 Function for Search  START                                                   *
     *****************************************************************************************************/

    /**
     * For All result max 60
     * @return int|mixed|string
     */
    public function findAllSixtyCompanyEnabled()
    {
        $qb = $this->createQueryBuilder("cd")
            ->leftJoin("cd.printer", "p")
            ->leftJoin('p.company', "company")
            ->andWhere('company.isEnabled = true')
            ->orderBy("cd.updateAt", "ASC")
            ->setMaxResults(60);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Company|null $p_company
     * @param array|null $p_aPrinter
     * @param DateTime|null $p_dateStart
     * @param DateTime|null $p_dateEnd
     * @return int|mixed|string
     */
    public function findDeltasForSearchPage(Company $p_company = null, array $p_aPrinter= null, DateTime $p_dateStart= null, DateTime $p_dateEnd= null)
    {
        $qb = $this->createQueryBuilder("d")
            ->leftJoin("d.printer", "printer")->addSelect("printer");
        $qb->leftJoin('printer.company', "company");
        /** if company exist */
        if ($p_company instanceof Company)
        {
            $qb->andWhere($qb->expr()->eq('company', ':q_company'));
            $qb->setParameter(':q_company', $p_company);
        }
        /** if Printer exist */
        if (is_array($p_aPrinter) && count($p_aPrinter) > 0)
        {
            $qb->andWhere($qb->expr()->in('printer', ':q_printer'));
            $qb->setParameter(':q_printer', $p_aPrinter);
        }
        /** if $p_dateStart && $p_dateEnd exist */
        if ($p_dateStart instanceof DateTime && $p_dateEnd instanceof DateTime)
        {
            $qb->andWhere($qb->expr()->between("d.updateAt",':p_dateStart', ':p_dateEnd'))
                ->setParameter(":p_dateStart", $p_dateStart)
                ->setParameter(":p_dateEnd", $p_dateEnd);
        }
        /** if $p_dateStart exist */
        else if ($p_dateStart instanceof DateTime)
        {
            $qb->andWhere($qb->expr()->gte('d.updateAt', ':p_dateStart'))
                ->setParameter(":p_dateStart", $p_dateStart);
        }
        /** if $p_dateEnd exist */
        else if ($p_dateEnd instanceof DateTime)
        {
            $qb->andWhere($qb->expr()->lte('d.updateAt', ':p_dateEnd'))
                ->setParameter(":p_dateEnd", $p_dateEnd);
        }
        $qb->andWhere($qb->expr()->eq('company.isEnabled', ":q_enabled"));
        $qb->setParameter(":q_enabled", true);

        $qb->addOrderBy('d.updateAt');

        return $qb->getQuery()->getResult();
    }

    /*****************************************************************************************************
     *                                                 Function for Search  END                                                     *
     ****************************************************************************************************

     /**
     * this function retrieve all deltas between two date
     * @param $printer
     * @param $datem
     * @return int|mixed|string
     */
    public function findThreeMonthLast($printer ,$datem)
    {
        $qb = $this->createQueryBuilder("delta")
            ->where("delta.updateAt BETWEEN :leastThreeMonths AND CURRENT_DATE()")
            ->andWhere("delta.printer = :printer")
            ->setParameter("leastThreeMonths", $datem)
            ->setParameter("printer", $printer)
            ->orderBy("delta.updateAt", "ASC");
        return $qb->getQuery()->getResult();
    }
}
