<?php

namespace App\Form;

use App\Entity\Consumable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('yellow',  PercentType::class, [
                "label" => "consumable.label.yellow"
            ])
            ->add('magenta',  PercentType::class, [
                "label" => "consumable.label.magenta"
            ])
            ->add('cyan',  PercentType::class, [
                "label" => "consumable.label.cyan"
            ])
            ->add('black',  PercentType::class, [
                "label" => "consumable.label.black"
            ])
            ->add('A3M', IntegerType::class, [
                "label" => "consumable.label.A3M"
            ])
            ->add('A3C', IntegerType::class, [
                "label" => "consumable.label.A3C"
            ])
            ->add('A4M', IntegerType::class, [
                "label" => "consumable.label.A4M"
            ])
            ->add('A4C', IntegerType::class, [
                "label" => "consumable.label.A4C"
            ])
            ->add('PPT', IntegerType::class, [
                "label" => "consumable.label.PPT"
            ])
            ->add('MPP', IntegerType::class, [
                "label" => "consumable.label.MPP"
            ])
            ->add('CPP', IntegerType::class, [
                "label" => "consumable.label.CPP"
            ])
            ->add('A3DM', IntegerType::class, [
                "label" => "consumable.label.A3DM"
            ])
            ->add('A4DM', IntegerType::class, [
                "label" => "consumable.label.A4DM"
            ])
            ->add('A3DC', IntegerType::class, [
                "label" => "consumable.label.A3DC"
            ])
            ->add('A4DC', IntegerType::class, [
                "label" => "consumable.label.A4DC"
            ])
            ->add('MBR', PercentType::class, [
                "label" => "consumable.label.MBR"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['style' => "display:flex; justify-content:center" ],
            'data_class' => Consumable::class,
            'translation_domain' => "EpsonProjectTrans"
        ]);
    }
}
