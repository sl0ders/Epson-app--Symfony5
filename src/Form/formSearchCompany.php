<?php


namespace App\Form;


use App\Entity\Company;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class formSearchCompany extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('company', EntityType::class, [
            "label" => "printer.label.selectCompany",
            "attr" => ["class" => "form-control"],
            "choice_label" => "name",
            "required" => false,
            "class" => Company::class
        ])
        ->add("submit", SubmitType::class, [
            "label" => "company.search"
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'mapped' => false,
            "translation_domain" => "EpsonProjectTrans"
        ]);
    }
}
