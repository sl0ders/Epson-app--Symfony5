<?php

namespace App\Datatables;

use App\Entity\Company;
use Exception;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class CompanyDatatable
 *
 * @package App\Datatables
 */
class CompanyDatatable extends AbstractDatatable
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
            ->add("code", Column::class, [
                'title' => $this->translator->trans('company.label.code', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, [
                    "search_type" => "eq"
                ]),
            ])
            ->add("name", Column::class, [
                'title' => $this->translator->trans('company.label.name', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, [
                    "search_type" => "eq"
                ]),
            ])
            ->add("email", Column::class, [
                'title' => $this->translator->trans('company.label.email', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, [
                    "search_type" => "eq"
                ]),
            ])
            ->add("socialReason", Column::class, [
                'title' => $this->translator->trans('company.label.socialReason', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, [
                    "search_type" => "eq"
                ]),
            ])
            ->add("city", Column::class, [
                'title' => $this->translator->trans('addressableTrait.label.city', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, [
                    "search_type" => "eq"
                ]),
            ])
            ->add('isEnabled', Column::class, [
                "visible" => false,
                "title" => "actif"
            ])
            ->add(null, ActionColumn::class, array(
                'start_html' => '<div class="start_actions" style="width:60px; text-align: center; margin: auto">',
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'end_html' => '</div>',
                'actions' => [
                    [
                        'route' => "admin_company_show",
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-eye',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('company.link.show', [], 'EpsonProjectTrans'),
                            'class' => 'btn btn-primary btn-xs m-auto btn-sm',
                            'role' => 'button'
                        ],
                    ], [
                        'route' => 'admin_company_enabled',
                        'route_parameters' => ['id' => 'id'],
                        'icon' => 'fa fa-toggle-off',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'class' => 'btn btn-danger btn-xs btn-sm m-auto',
                            'role' => 'button',
                            'title' => $this->translator->trans('company.link.isEnable', [], 'EpsonProjectTrans'),
                        ],
                        'render_if' => function ($row): bool {
                            return !$row['isEnabled'];
                        },
                    ], [
                        'route' => 'admin_company_enabled',
                        'route_parameters' => ['id' => 'id'],
                        'icon' => 'fa fa-toggle-on',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'class' => 'btn btn-success btn-xs btn-sm m-auto',
                            'role' => 'button',
                            'title' => $this->translator->trans('company.link.desarchived', [], 'EpsonProjectTrans'),
                        ],
                        'render_if' => function ($row): bool {
                            return $row['isEnabled'];
                        },
                    ], [
                        'route' => 'admin_company_edit',
                        'route_parameters' => ['id' => 'id'],
                        'icon' => 'fa fa-edit',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'class' => 'btn btn-warning btn-xs btn-sm m-auto',
                            'role' => 'button',
                            'title' => $this->translator->trans('company.link.edit', [], 'EpsonProjectTrans'),
                        ]
                    ],
                ],
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity(): string
    {
        return Company::class;
    }

    public function getName(): string
    {
        return "company_datatable";
    }
}
