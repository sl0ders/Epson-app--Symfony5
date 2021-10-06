<?php

namespace App\Form;

use App\Entity\Company;
use NumberFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "company.label.name",
                "required" => false
            ])
            ->add('isEnabled', CheckboxType::class, [
                "label" => "company.label.isEnabled",
                "required" => false
            ])
            ->add("logo", VichImageType::class, [
                "label" => "company.label.logo",
                "attr" => [
                    "class" => "form-control"
                ],
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => true,
                'imagine_pattern' => 'my_thumb',
                'asset_helper' => true,
            ])
            ->add("bacBreakingUpDays", IntegerType::class, [
                "label" => "company.label.bacBreakingUpDays"
            ])
            ->add("bacBreakingUpLvl", PercentType::class, [
                "label" => "company.label.bacBreakingUpLvl",
                "type" => "integer"
            ])
            ->add("inkBreakingUpDays", IntegerType::class, [
                "label" => "company.label.inkBreakingUpDays"
            ])
            ->add("inkBreakingUpLvl", PercentType::class, [
                "label" => "company.label.inkBreakingUpLvl",
                "type" => "integer"
            ])
            ->add('email', EmailType::class, [
                "label" => "company.label.email",
                "required" => false
            ])
            ->add('phone', TextType::class, [
                "label" => "company.label.phone",
                "required" => false
            ])
            ->add('fax', EmailType::class, [
                "label" => "company.label.fax",
                "required" => false
            ])
            ->add('website', UrlType::class, [
                "label" => "company.label.website",
                "required" => false
            ])
            ->add('socialReason', TextType::class, [
                "label" => "company.label.socialReason",
                "required" => false
            ])
            ->add('siren', TextType::class, [
                "label" => "company.label.siren",
                "required" => false
            ])
            ->add('siret', TextType::class, [
                "label" => "company.label.siret",
                "required" => false
            ])
            ->add('shortDescription', TextareaType::class, [
                "label" => "company.label.description",
                "required" => false
            ])
            ->add('address', AddressableTraitType::class, [
                "required" => false
            ])
            ->add("submit", SubmitType::class, [
                "label" => "button.save",
                "attr" => ["class" => "mt-3 btn btn-primary btn-sm pull-left"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "translation_domain" => "EpsonProjectTrans",
            'data_class' => Company::class,
        ]);
    }
}
