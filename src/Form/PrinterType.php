<?php

namespace App\Form;

use App\Entity\Printer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrinterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "printer.label.name",
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('office', TextType::class, [
                "label" => "printer.label.place",
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('ip', TextType::class, [
                "label" => "printer.label.ip",
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('mac', TextType::class, [
                "label" => "printer.label.mac",
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('sn', TextType::class, [
                "label" => "printer.label.serialNumber",
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('swv', TextType::class, [
                "label" => "printer.label.firmwareVersion",
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('des', TextType::class, [
                "label" => "printer.label.model",
                "attr" => [
                    "class" => "form-control"
                ]
            ])
        ->add("consumable", ConsumableType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'EpsonProjectTrans',
            'data_class' => Printer::class,
        ]);
    }
}
