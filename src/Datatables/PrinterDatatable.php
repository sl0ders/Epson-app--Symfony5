<?php

namespace App\Datatables;

use App\Entity\Printer;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Filter\Select2Filter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class PrinterDatatable
 *
 * @package App\Datatables
 */
class PrinterDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function buildDatatable(array $options = array())
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
        ));

        $this->columnBuilder
            ->add("company.name", Column::class, [
                'title' => $this->translator->trans("printer.title.company", [], "EpsonProjectTrans")
            ])
            ->add('des', Column::class, array(
                'title' => $this->translator->trans('printer.label.model', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, array()),
            ))
            ->add('name', Column::class, array(
                'title' => $this->translator->trans('printer.label.name', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, array()),
            ))
            ->add('subname', Column::class, array(
                'title' => $this->translator->trans('printer.label.subname', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, array()),
            ))
            ->add('ip', Column::class, array(
                'title' => $this->translator->trans('printer.label.ip', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ))
            ->add('mac', Column::class, array(
                'title' => $this->translator->trans('printer.label.mac', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ))
            ->add("sn", Column::class, array(
                'title' => $this->translator->trans('printer.label.serialNumber', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, array()
                )
            ))
            ->add(null, ActionColumn::class, array(
                'start_html' => '<div class="start_actions" style="width:50px">',
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'end_html' => '</div>',
                'actions' => [
                    [
                        'route' => "user_printer_show",
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-eye',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('printer.link.show', [], 'EpsonProjectTrans'),
                            'class' => 'btn btn-primary btn-xs m-auto',
                            'role' => 'button'
                        ],
                    ],
                ],
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return Printer::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'printer_datatable';
    }
}
