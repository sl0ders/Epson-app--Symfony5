<?php


namespace App\Datatables;


use App\Entity\User;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class UserDatatable
 *
 * @package App\Datatables
 */
class UserDatatable extends AbstractDatatable
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
            ->add("email", Column::class, [
                'title' => $this->translator->trans("user.title.email", [], "EpsonProjectTrans")
            ])
            ->add("roles", Column::class, [
                'title' => $this->translator->trans("user.title.roles", [], "EpsonProjectTrans")
            ])
            ->add("company.name", Column::class, [
                'title' => $this->translator->trans("user.title.company", [], "EpsonProjectTrans"),
                "default_content" => ""
            ])
            ->add('isEmailRecipient', Column::class, [
                "visible" => false,
                "title" => "actif"
            ]);
        $this->columnBuilder
            ->add(null, ActionColumn::class, array(
                "width" => "100px",
                'start_html' => '<div class="start_actions" style="width:80px; text-align: center">',
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'end_html' => '</div>',
                'class_name' => 'text-center m-auto',
                'actions' => [
                    [
                        'route' => "user_show",
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-eye',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('user.link.show', [], 'EpsonProjectTrans'),
                            'class' => 'btn btn-primary btn-sm m-auto mr-2',
                            'role' => 'button'
                        ],
                    ],[
                        'route' => "user_edit",
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-edit',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('user.link.show', [], 'EpsonProjectTrans'),
                            'class' => 'btn btn-warning btn-sm m-auto',
                            'role' => 'button'
                        ],
                    ]
                ],
            ));;
    }

    /**
     * @inheritDoc
     */
    public function getEntity()
    {
        return User::class;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return "user_datatable";
    }
}
