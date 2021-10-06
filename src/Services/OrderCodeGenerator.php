<?php


namespace App\Services;


use App\Entity\OrderCartridge;
use App\Repository\OrderCartridgeRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderCodeGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var OrderCartridgeRepository
     */
    private $orderCartridgeRepository;

    /**
     * NumberSaleOrderGenerator constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param OrderCartridgeRepository $orderCartridgeRepository
     */
    public function __construct(EntityManagerInterface $entityManager, OrderCartridgeRepository $orderCartridgeRepository)
    {
        $this->em = $entityManager;
        $this->orderCartridgeRepository = $orderCartridgeRepository;
    }

    /**
     * Define automatic code
     * Format :
     * $code - take code Order
     * $increment - Take the last code + 1
     *
     * @param OrderCartridge $orderCartridge
     * @return string
     */
    public function generate(OrderCartridge $orderCartridge): string
    {
        $code = $orderCartridge->getPrinter()->getCompany()->getCode();
        $prefix = $code . '-CC';

        $maxCode = $this->orderCartridgeRepository->findMaxNumberWithPrefix($prefix);

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
        return 'epson_project.generator.order_cartridge_number';
    }
}
