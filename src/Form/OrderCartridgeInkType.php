<?php

namespace App\Form;

use App\Entity\OrderCartridge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderCartridgeInkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orderAt', DateType::class, [
                "label" => "order.cartridge.label.orderDate",
                'widget' => 'single_text',
                "attr" => ["class" => "form-control"]
            ])
            ->add('printer', TextType::class, [
                "label" => "order.cartridge.label.printer",
                "attr" => ["class" => "form-control", "readonly" => "false"],
                "mapped" => false
            ])
            ->add('client', TextType::class, [
                "label" => "order.cartridge.label.client",
                "attr" => ["class" => "form-control", "readonly" => "false"],
                "mapped" => false
            ])
            ->add('cartridge', TextType::class, array(
                "label" => "order.cartridge.label.cartridge",
                "attr" => ["class" => "form-control", "readonly" => "false"],
                "mapped" => false

            ))
            ->add("quantity", IntegerType::class, [
                "label" => "order.cartridge.label.quantity",
                "attr" => [
                    "class" => "select2",
                    "min" => 1,
                    "max" => 10,
                    "value" => 1
                ],
            ])
            ->add('note', TextareaType::class, [
                "required" => false,
                "label" => "order.cartridge.label.note",
                "attr" => ["class" => "form-control"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "translation_domain" => "EpsonProjectTrans",
            'data_class' => OrderCartridge::class,
        ]);
    }
}
