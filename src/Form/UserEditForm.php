<?php


namespace App\Form;


use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("identity", IdentityTraitType::class, [

                "data_class" => User::class,
                "label" => null
            ])
            ->add("address", AddressableTraitType::class, [
                "required" => false,
                "data_class" => User::class,
                "label" => null
            ])
            ->add('roles', ChoiceType::class, [
                "required" => true,
                "placeholder" => "placeholder.choices",
                "label" => "user.label.roles",
                "multiple" => true,
                "attr" => [
                    'class' => "select2"
                ],
                "choices" => [
                    "ROLE_ADMIN"=> "ROLE_ADMIN",
                    "ROLE_USER" => "ROLE_USER"
                ]
            ])
            ->add("company", EntityType::class, [
                "class" => Company::class,
                "label" => "Entreprise",
                "choice_label" => "name",
            ])
            ->add('isEmailRecipient' , CheckboxType::class, [
                "label" => "user.link.isEmailRecipient",
                "required" => false,
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
            'data_class' => User::class,
        ]);
    }
}
