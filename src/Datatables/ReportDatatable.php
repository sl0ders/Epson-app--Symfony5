<?php

namespace App\Datatables;

use App\Entity\Notification;
use App\Entity\Report;
use Exception;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class NotificationDatatable
 *
 * @package App\Datatables
 */
class ReportDatatable extends AbstractDatatable
{

    /**
     * @return callable|Closure|null
     */
    public function getLineFormatter()
    {
        $formatter = function ($row) {
            $row['subjectVirtual'] = $this->translator->trans($row['subject'], [], 'EpsonProjectTrans');
            return $row;
        };

        return $formatter;
    }

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
            ->add("reportCode", Column::class, [
                'title' => $this->translator->trans('report.label.reportCode', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, array()),
            ])
            ->add("createdAt", DateTimeColumn::class, [
                'title' => $this->translator->trans('report.label.date', [], 'EpsonProjectTrans'),
                "date_format" => 'DD-MM-YYYY H:m:s',
                'searchable' => true,
                'orderable' => true,
                "width" => "40px",
                'filter' => array(TextFilter::class, array()),
            ])
            ->add("company.name", Column::class, [
                'title' => $this->translator->trans('report.label.company', [], 'EpsonProjectTrans'),
                'searchable' => true,
                'orderable' => true,
                'filter' => array(TextFilter::class, array())
            ])
            ->add("subject", Column::class, [
                'title' => $this->translator->trans('report.label.subject', [], 'EpsonProjectTrans'),
                "visible" => false
            ])
            ->add("subjectVirtual", VirtualColumn::class, [
                'title' => $this->translator->trans('report.label.subject', [], 'EpsonProjectTrans'),
                'orderable' => true,
                'order_column' => 'type',
                'width' => '75px',
            ])
            ->add('statut', Column::class,[
                "visible" => false,
                "title" => "actif"
            ])
            ->add(null, ActionColumn::class, array(
                'start_html' => '<div class="start_actions" style="width:60px; text-align: center; margin:auto">',
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'end_html' => '</div>',
                'actions' => [
                    [
                        'route' => "user_report_show",
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-eye',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('report.link.show', [], 'EpsonProjectTrans'),
                            'class' => 'btn btn-primary btn-xs m-auto btn-sm',
                            'role' => 'button'
                        ],
                    ], [
                        'route' => 'user_report_enabled',
                        'route_parameters' => ['id' => 'id'],
                        'icon' => 'fa fa-toggle-off',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'class' => 'btn btn-danger btn-xs m-auto btn-sm',
                            'role' => 'button',
                            'title' => $this->translator->trans('report.link.archived', [], 'EpsonProjectTrans'),
                        ],
                        'render_if' => function ($row): bool {
                            return !$row['statut'];
                        },
                    ], [
                        'route' => 'user_report_enabled',
                        'route_parameters' => ['id' => 'id'],
                        'icon' => 'fa fa-toggle-on',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'class' => 'btn btn-primary btn-xs m-auto btn-sm',
                            'role' => 'button',
                            'title' => $this->translator->trans('report.link.desarchived', [], 'EpsonProjectTrans'),
                        ],
                        'render_if' => function ($row): bool {
                            return $row['statut'];
                        },
                    ],
                ],
            ));
    }


    /**
     * {@inheritdoc}
     */
    public function getEntity(): string
    {
        return Report::class;
    }

    public function getName(): string
    {
        return "report_datatable";
    }
}
