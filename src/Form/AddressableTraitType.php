<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressableTraitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('zipCode', IntegerType::class, [
                "label" => "addressableTrait.label.zipCode",
            ])
            ->add('city', TextType::class, [
                "label" => "addressableTrait.label.city",
            ])
            ->add('country', TextType::class, [
                "label" => "addressableTrait.label.country",
            ])
            ->add('street', TextType::class, [
                "label" => "addressableTrait.label.street",
            ])
            ->add('street_number', IntegerType::class, [
                "label" => "addressableTrait.label.streetNumber",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => "EpsonProjectTrans",
            'inherit_data' => true
        ]);
    }
}
