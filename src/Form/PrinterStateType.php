<?php


namespace App\Form;


use App\Entity\Printer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrinterStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("state", ChoiceType::class, [
                "label" => "printer.label.state",
                "choices" => [
                    Printer::STATE_OPERATIONAL => Printer::STATE_OPERATIONAL,
                    Printer::STATE_AWAITING_MAINTENANCE => Printer::STATE_AWAITING_MAINTENANCE,
                    Printer::STATE_BROKEN => Printer::STATE_BROKEN
                ]
            ])
        ->add("submit", SubmitType::class, [
            "label" => "printer.btn.changeState"
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'EpsonProjectTrans',
            'data_class' => Printer::class
        ]);
    }
}
