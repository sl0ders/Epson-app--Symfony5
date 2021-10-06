<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdentityTraitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                "label" => "user.label.firstname",
            ])
            ->add('lastname',TextType::class, [
                "label" => "user.label.lastname",
            ])
            ->add('phone', TextType::class, [
                "label" => "user.label.phone",
            ])
            ->add('email', EmailType::class, [
                "label" => "user.label.email",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => "EpsonProjectTrans",
            'inherit_data' => true,
            "label" => false
        ]);
    }
}
