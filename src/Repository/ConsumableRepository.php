<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Consumable;
use App\Entity\Printer;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Consumable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consumable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consumable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsumableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consumable::class);
    }

    /**
     * this function retrieve the sixty last consumable of the company enabled
     * @return int|mixed|string
     */
    public function findAllSixtyCompanyEnabled()
    {
        $qb = $this->createQueryBuilder("c")
            ->leftJoin("c.print", "p")
            ->leftJoin('p.company', "company")
            ->andWhere('company.isEnabled = true')
            ->orderBy("c.date_update", "DESC")
            ->orderBy("c.print");
        return $qb->getQuery()->getResult();
    }

    /**
     * This function retrieve the last lvl cartridge and retrieve bac of an printer
     * @param $printer
     * @return int|mixed|string
     */
    public function findLastLvlByPrint($printer)
    {
        $qb = $this->createQueryBuilder("c")
            ->leftJoin('c.print', "p")
            ->orderBy("c.date_update", "DESC")
            ->select("c.MBR", 'c.black', "c.cyan", "c.magenta", "c.yellow", "c.date_update")
            ->where("p = :printer")
            ->setParameter("printer", $printer)
            ->setMaxResults(1);
        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
            return 'no enought information';
        }
    }

    /*****************************************************************************************************
     *                                                 Function for search date                                                     *
     ****************************************************************************************************/

    /**
     * @param $printer
     * @param $datem
     * @return int|mixed|string
     */
    public function findConsumableByPrinter($printer, $datem)
    {
        $qb = $this->createQueryBuilder("c")
            ->leftJoin("c.print", "p")->addSelect("p")
            ->orderBy("c.date_update", "ASC");
        $qb->where($qb->expr()->eq('p', ':printer'))
            ->andWhere("c.date_update BETWEEN :leastThreeMonths AND CURRENT_DATE()")
            ->setParameter(":printer", $printer)
            ->setParameter("leastThreeMonths", $datem);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Company|null $p_company
     * @param array|null $p_aPrinter
     * @param DateTime|null $p_dateStart
     * @param DateTime|null $p_dateEnd
     * @return int|mixed|string
     */
    public function findConsumableForSearchPage(Company $p_company = null, array $p_aPrinter= null, DateTime $p_dateStart= null, DateTime $p_dateEnd= null)
    {
        $qb = $this->createQueryBuilder("c")
            ->leftJoin("c.print", "printer")->addSelect("printer");
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
            $qb->andWhere($qb->expr()->between("c.date_update",':p_dateStart', ':p_dateEnd'))
                ->setParameter(":p_dateStart", $p_dateStart)
                ->setParameter(":p_dateEnd", $p_dateEnd);
        }
        /** if $p_dateStart exist */
        else if ($p_dateStart instanceof DateTime)
        {
            $qb->andWhere($qb->expr()->gte('c.date_update', ':p_dateStart'))
                ->setParameter(":p_dateStart", $p_dateStart);
        }
        /** if $p_dateEnd exist */
        else if ($p_dateEnd instanceof DateTime)
        {
            $qb->andWhere($qb->expr()->lte('c.date_update', ':p_dateEnd'))
                ->setParameter(":p_dateEnd", $p_dateEnd);
        }
        $qb->andWhere($qb->expr()->eq('company.isEnabled', ":q_enabled"));
        $qb->setParameter(":q_enabled", true);

        $qb->addOrderBy('c.date_update');

        return $qb->getQuery()->getResult();
    }

    /**
     * TODO A optimiser
     * @param $company
     * @param $start
     * @param $end
     * @return int|mixed|string
     */
    public function findConsumableByCompanyDateStartDateEnd($company, $start, $end)
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin("c.print", 'p')->addSelect("p")
            ->leftJoin('p.company', "company")
            ->andWhere("company.isEnabled = true")
            ->where('company = :company');
        $qb->andWhere($qb->expr()->between("c.date_update", ':start', ':end'))
            ->setParameter(":start", $start)
            ->setParameter(":end", $end)
            ->setParameter('company', $company);
        return $qb->getQuery()->getResult();
    }

    /*****************************************************************************************************
     *                                                 Function for search date   END                                             *
     ****************************************************************************************************/

    /**
     * this function get the last print number for one print
     * @param $printer
     * @return QueryBuilder
     */
    public function getConsumable($printer): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where("c.print = :printer")
            ->setParameter(':printer', $printer)
            ->orderBy("c.date_update")
            ->select("c.PPT")
            ->setMaxResults(1)
            ->leftJoin('p.company', "company")
            ->andWhere('company.isEnabled = true');

    }

    /**
     * @param $printer
     * @return QueryBuilder
     */
    public function getBaseForCompareInkLvl($printer): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where("c.print = :printer")
            ->setParameter(':printer', $printer)
            ->orderBy("c.date_update")
            ->select("c.PPT")
            ->setMaxResults(1);
    }
    /**
     * this function retrieve the total color print if lvl > 90 of yellow cartridge
     * @param $printer
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findYellowMax($printer)
    {
        $qb = $this->getBaseForCompareInkLvl($printer)
            ->andWhere('c.yellow > 90')
            ->select('c.CPP');
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * min yellow
     * this function retrieve the total color print if lvl < 10 of yellow cartridge for compare in printer manage
     * @param $printer
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findYellowMin($printer)
    {
        $qb = $this->getBaseForCompareInkLvl($printer)
            ->andWhere('c.yellow < 10')
            ->select('c.CPP');
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * max magenta
     * this function retrieve the total color print if lvl > 90 of magenta cartridge for compare in printer manage
     * @param Printer|null $printer
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findMagentaMax(?Printer $printer)
    {
        $qb = $this->getBaseForCompareInkLvl($printer)
            ->andWhere('c.magenta > 90')
            ->select('c.CPP');
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * min magenta
     * this function retrieve the total color print if lvl < 10 of magenta cartridge for compare in printer manage
     * @param $printer
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findMagentaMin($printer)
    {
        $qb = $this->getBaseForCompareInkLvl($printer)
            ->andWhere('c.magenta < 10')
            ->select('c.CPP');
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * max cyan
     * this function retrieve the total color print if lvl > 90 of cyan cartridge for compare in printer manage
     * @param Printer|null $printer
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findCyanMax(?Printer $printer)
    {
        $qb = $this->getBaseForCompareInkLvl($printer)
            ->andWhere('c.cyan > 90')
            ->select('c.CPP');
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * min Cyan
     * this function retrieve the total color print if lvl < 10 of cyan cartridge for compare in printer manage
     * @param $printer
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findCyanMin($printer)
    {
        $qb = $this->getBaseForCompareInkLvl($printer)
            ->andWhere('c.cyan < 10')
            ->select('c.CPP');
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * max black
     * this function retrieve the total b&w print if lvl > 90 of black cartridge for compare in printer manage
     * @param Printer|null $printer
     * @return int|mixed|string
     */
    public function findBlackMax(?Printer $printer)
    {
        $qb = $this->getBaseForCompareInkLvl($printer)
            ->andWhere('c.black > 90')
            ->select('c.MPP');
        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException | NonUniqueResultException $e) {
        }
    }

    /**
     * min black
     * this function retrieve the total b&w print if lvl < 10 of black cartridge for compare in printer manage
     * @param $printer
     * @return NonUniqueResultException|NoResultException|\Exception|int|mixed|string
     */
    public function findBlackMin($printer)
    {
        $qb = $this->getBaseForCompareInkLvl($printer)
            ->andWhere('c.black < 10')
            ->select('c.MPP');
        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return $e;
        }
    }

    /**
     * max bac
     * this function retrieve the total b&w print if lvl =0 of retrieve bac for compare in printer manage
     * @param Printer|null $printer
     * @return int|mixed|string
     */
    public function findBacMax(?Printer $printer)
    {
        $qb = $this->getBaseForCompareInkLvl($printer)
            ->andWhere('c.MBR = 0')
            ->select('c.MPP');
        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException | NonUniqueResultException $e) {
        }
    }

    /**
     * min bac
     * this function retrieve the total b&w print if lvl > 90 of retrieve bac for compare in printer manage
     * @param Printer|null $printer
     * @return int|mixed|string
     */
    public function findBacMin(?Printer $printer)
    {
        $qb = $this->getBaseForCompareInkLvl($printer)
            ->andWhere('c.MBR > 90')
            ->select('c.MPP');
        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException | NonUniqueResultException $e) {
        }
    }

    /**
     * This function retrieve all consumblable for an company
     * @param $company
     * @return int|mixed|string
     */
    public function findConsumableByCompany($company)
    {
        $qb = $this
            ->createQueryBuilder("c")
            ->leftJoin("c.print", "p")
            ->leftJoin('p.company', "company")
            ->andWhere('company.isEnabled = true')
            ->where("p.company = :company")
            ->setParameter("company", $company)
            ->setMaxResults(60);
        return $qb->getQuery()->getResult();
    }

    /**
     * this function retrieve the ten last consumables for an printer order by desc
     * @param $printer
     * @return int|mixed|string
     */
    public function FindTenLastConsumables($printer)
    {
        $qb = $this->createQueryBuilder("consumable")
            ->andWhere("consumable.print = :printer")
            ->setMaxResults(10)
            ->setParameter("printer", $printer)
            ->orderBy("consumable.date_update", "DESC");
        return $qb->getQuery()->getResult();
    }
}
