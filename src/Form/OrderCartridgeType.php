<?php

namespace App\Form;

use App\Entity\Cartridge;
use App\Entity\Company;
use App\Entity\OrderCartridge;
use App\Entity\Printer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderCartridgeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orderCode', TextType::class, [
                "label" => "order.cartridge.label.orderCode",
                "disabled" => true,
                "attr" => ["class" => "form-control"]
            ])
            ->add("quantity", IntegerType::class, [
                "label" => "order.cartridge.label.quantity",
                "attr" => [
                    "class" => "select2",
                    "min" => 1,
                    "max" => 10
                ],
            ])
            ->add('note', TextareaType::class, [
                "label" => "order.cartridge.label.note",
                "attr" => ["class" => "form-control"],
                "required" => false
            ])
            ->add('orderAt', DateType::class, [
                "label" => "order.cartridge.label.orderDate",
                'widget' => 'single_text',
                "disabled" => true,
                "attr" => ["class" => "form-control","readonly" =>"false"],
            ])
            ->add('state',ChoiceType::class, [
                "label" => "order.cartridge.label.state",
                "choices" => [
                    OrderCartridge::VALIDATE_COMMANDED => OrderCartridge::VALIDATE_COMMANDED,
                    OrderCartridge::VALIDATE_INPREPARATION => OrderCartridge::VALIDATE_INPREPARATION,
                    OrderCartridge::VALIDATE_SENDED => OrderCartridge::VALIDATE_SENDED,
                    OrderCartridge::VALIDATE_REPORTED => OrderCartridge::VALIDATE_REPORTED
                ],
                "attr" => ["class" => "select2 form-control"]
            ])
            ->add('cartridge', EntityType::class, [
                "label" => "order.cartridge.label.cartridge",
                "class" => Cartridge::class,
                "disabled" => true,
                "choice_label" => "name",
                "attr" => ["class" => "form-control","readonly" =>"false"],

            ])
            ->add('client', EntityType::class, [
                "label" => "order.cartridge.label.client",
                "class" => Company::class,
                "choice_label" => "name",
                "disabled" => true,
                "attr" => ["class" => "form-control","readonly" =>"false"],
            ])
            ->add('printer', EntityType::class, [
                "label" => "order.cartridge.label.printer",
                "class" => Printer::class,
                "disabled" => true,
                "choice_label" => "name",
                "attr" => ["class" => "form-control","readonly" =>"false"],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "translation_domain" => "EpsonProjectTrans",
            'data_class' => OrderCartridge::class,
        ]);
    }
}
