<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Printer;
use App\Repository\CompanyRepository;
use App\Repository\PrinterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SearchFormType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $entity;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(EntityManagerInterface $entity, TokenStorageInterface $tokenStorage)
    {
        $this->entity = $entity;
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (count($this->tokenStorage->getToken()->getUser()->getRoles()) == 2) {
            $builder->add('company', EntityType::class, [
                "label" => "printer.label.selectCompany",
                "query_builder" => function (CompanyRepository $companyRepository) {
                    if (count($this->tokenStorage->getToken()->getUser()->getRoles()) == 1) {
                        return $companyRepository->getCompanyEnabled();
                    }
                },
                "choice_label" => "name",
                'required' => true,
                "class" => Company::class
            ]);
        }
        $builder->add("dateStart", DateTimeType::class, [
            'widget' => 'single_text',
            'required' => false,
            'label' => "printer.label.dateStart",
        ])
            ->add("dateEnd", DateTimeType::class, [
                'label' => "printer.label.dateEnd",
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('printer', EntityType::class, [
                "class" => Printer::class,
                "choice_label" => "name",
                "required" => false,
                "label" => "printer.label.printers",
                "query_builder" => function (PrinterRepository $printerRepository) {
                    if (count($this->tokenStorage->getToken()->getUser()->getRoles()) == 1) {
                        return $printerRepository->getPrinterOfCompanyUser($this->tokenStorage->getToken()->getUser()->getCompany());
                    }
                }
            ])
            ->add('all', CheckboxType::class, [
                "mapped" => false,
                "label" => "printer.label.all",
                "required" => false
            ]);
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
