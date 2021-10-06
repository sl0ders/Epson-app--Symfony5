<?php

namespace App\Form;

use App\Entity\Cartridge;
use App\Entity\OrderCartridge;
use App\Entity\Printer;
use App\Entity\Report;
use App\Repository\CartridgeRepository;
use App\Repository\OrderCartridgeRepository;
use App\Repository\PrinterRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject',ChoiceType::class, [
                "label" => "report.label.subject",
                "choices" =>[
                    Report::SUBJECT_INK => Report::SUBJECT_INK,
                    Report::SUBJECT_PRINT => Report::SUBJECT_PRINT,
                    Report::SUBJECT_ORDER => Report::SUBJECT_ORDER,
                ]
            ])
            ->add("printer", EntityType::class, [
                "class" => Printer::class,
                "label" => " ",
                "required" => false,
                'choice_label' => "name",
                "attr" => [
                    "class" => "printerReport"
                ],
                "query_builder" => function(PrinterRepository $printerRepository) use ($options) {
                    return $printerRepository->createQueryBuilder('printer')
                        ->andWhere('printer.company=:company')
                        ->setParameter("company", $options['company']);
                }
            ])
            ->add("ink", EntityType::class, [
                "class" => Cartridge::class,
                'choice_label' => "name",
                "label" => " ",
                "required" => false,
                "attr" => [
                    "class" => "cartridgeReport"
                ],
                "query_builder" => function(CartridgeRepository $cartridgeRepository) use ($options) {
                    return $cartridgeRepository->createQueryBuilder('cartridge')
                        ->leftJoin("cartridge.printer", "printer")
                        ->andWhere('printer.company=:company')
                        ->setParameter("company", $options['company']);
                }
            ])
            ->add("orderCartridge", EntityType::class, [
                "class" => OrderCartridge::class,
                'choice_label' => "orderCode",
                "required" => false,
                "label" => " ",
                "attr" => [
                    "class" => "orderReport"
                ],
                "query_builder" => function(OrderCartridgeRepository $orderCartridgeRepository) use ($options) {
                    return $orderCartridgeRepository->createQueryBuilder('order_cartridge')
                        ->andWhere('order_cartridge.client=:company')
                        ->setParameter("company", $options['company']);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'company' => false,
            "translation_domain" => "EpsonProjectTrans",
            'data_class' => Report::class,
        ]);
    }
}
