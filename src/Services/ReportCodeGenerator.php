<?php


namespace App\Services;


use App\Entity\Report;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReportCodeGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ReportRepository
     */
    private $reportRepository;

    /**
     * NumberSaleOrderGenerator constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ReportRepository $reportRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ReportRepository $reportRepository)
    {
        $this->em = $entityManager;
        $this->reportRepository = $reportRepository;
    }

    /**
     * Define automatic code
     * Format :
     * $code - take code company
     * $increment - Take the last code + 1
     *
     * @param Report $report
     * @return string
     */
    public function generate(Report $report): string
    {
        $code = $report->getCompany()->getCode();
        $prefix = $code . '-RR';

        $maxCode = $this->reportRepository->findMaxNumberWithPrefix($prefix);

        if ($maxCode) {
            $increment = substr($maxCode[1], -6);
            $increment = (int)$increment + 1;

        } else
            $increment = 0;

        $code = $prefix . sprintf('%06d', $increment);

        return $code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'epson_project.generator.report_number';
    }
}
