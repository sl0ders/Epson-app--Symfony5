<?php

namespace App\Datatables;

use App\Entity\Company;
use App\Entity\OrderCartridge;
use Exception;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class CompanyDatatable
 *
 * @package App\Datatables
 */
class OrderCartridgeDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     * @throws Exception
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
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            "page_length" => 40
        ));
        $this->columnBuilder
            ->add("orderCode", Column::class, [
                'title' => $this->translator->trans('company.label.code', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, [
                    "search_type" => "eq"
                ]),
            ])
            ->add("cartridge.name", Column::class, [
                'title' => $this->translator->trans('cartridge.label.name', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, [
                    "search_type" => "eq"
                ]),
            ])
            ->add("quantity", Column::class, [
                'title' => $this->translator->trans('order.cartridge.label.quantity', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, [
                    "search_type" => "eq"
                ]),
            ])
            ->add("user.lastname", Column::class, [
                'title' => $this->translator->trans('user.label.fullname', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, [
                    "search_type" => "eq"
                ]),
            ])
            ->add("client.name", Column::class, [
                'title' => $this->translator->trans('company.label.name', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, [
                    "search_type" => "eq"
                ]),
            ])
            ->add('createdAt', DateTimeColumn::class, [
                "visible" => false,
                "title" => "actif"
            ])
            ->add('orderAt', DateTimeColumn::class, [
                "visible" => false,
                "title" => "actif"
            ])
            ->add(null, ActionColumn::class, array(
                'start_html' => '<div class="start_actions" style="width:60px; text-align: center; margin: auto">',
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'end_html' => '</div>',
                'actions' => [
                    [
                        'route' => "user_order_cartridge_show",
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-eye',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('order.cartridge.link.show', [], 'EpsonProjectTrans'),
                            'class' => 'btn btn-primary btn-xs m-auto btn-sm',
                            'role' => 'button'
                        ],
                    ]
                ],
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity(): string
    {
        return OrderCartridge::class;
    }

    public function getName(): string
    {
        return "order_cartridge_datatable";
    }
}
