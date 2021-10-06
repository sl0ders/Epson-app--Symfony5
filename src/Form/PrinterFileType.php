<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PrinterFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("download", FileType::class, [
                "label" => "printer.text.selectXml",
                "attr" => ["class" => "form-control"],
                "mapped" => "false",
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'text/xml'
                        ],
                        'mimeTypesMessage' => 'printer.text.notGoodFile',
                    ])
                ],
            ])
            ->add("submit", SubmitType::class, [
                "label" => "printer.btn.add",
                "attr" => ['class' => "border text-white btn btn-sm btn-primary"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'EpsonProjectTrans',
            'data_class' => null
        ]);
    }
}
