<?php


namespace App\Form;

use App\Entity\Printer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrinterEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subname', TextType::class, [
                "label" => "printer.label.addSubname",
                "attr" => ["class" => "form-control"]
            ])
            ->add("submit", SubmitType::class, [
                "label" => "button.save",
                "attr" => ["class" => "btn btn-primary"]
            ]);
    }
        public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "translation_domain" => "EpsonProjectTrans",
            'data_class' => Printer::class,
        ]);
    }

}
