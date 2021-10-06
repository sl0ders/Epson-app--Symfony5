<?php

namespace App\Datatables;

use App\Entity\ConsumableDelta;
use Exception;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class DeltaDatatable
 *
 * @package App\Datatables
 */
class DeltaDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function buildDatatable(array $options = [])
    {
        $this->ajax->set(array(
            // send some extra example data
            'data' => array('data1' => 1, 'data2' => 2),
            // cache for 10 pages
            'pipeline' => 10
        ));

        $this->language->set(array(
            'cdn_language_by_locale' => true
        ));

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_4_STYLE,
            'stripe_classes' => ['strip1', 'strip2', 'strip3'],
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            "page_length" => 40
        ));

        $this->columnBuilder
            ->add("updateAt", DateTimeColumn::class, [
                'title' => $this->translator->trans('consumable.label.datatable.date', [], 'EpsonProjectTrans'),
                "date_format" => 'L',
                'searchable' => true,
                'orderable' => true,
                "width" =>"40px",
                'filter' => array(TextFilter::class, array()),
                'editable' => array(CombodateEditable::class, array(
                    'format' => 'DD-MM-YYYY',
                    'view_format' => 'DD.MM.YYYY'
                ))
            ])
            ->add("yellow_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.yellow', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("magenta_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.magenta', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("cyan_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.cyan', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("black_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.black', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("A3M_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.a3m', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("A3C_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.a3c', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("A3DM_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.a3dm', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("A3DC_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.a3dc', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("A4M_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.a4m', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("A4C_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.a4c', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("A4DM_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.a4dm', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("A4DC_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.a4dc', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("PPT_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.ppt', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("CPP_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.cpp', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("MPP_delta", Column::class, [
                'title' => $this->translator->trans('consumable.label.datatable.mpp', [], 'EpsonProjectTrans'),
                'searchable' => true,
                "width" =>"40px",
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ]);
    }

    public function getEntity()
    {
        return ConsumableDelta::class;
    }

    public function getName()
    {
        return "delta_datatable";
    }
}
