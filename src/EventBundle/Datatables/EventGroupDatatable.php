<?php
namespace EventBundle\Datatables;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
/**
 * Class EventGroupDatatable
 *
 * @package EventBundle/Datatables
 */
class EventGroupDatatable extends AbstractDatatableView
{


  /*  public function getLineFormatter()
    {
        $formatter = function($line) {
            $status = array(
                'Activate' => 'success',
                'Deactivate' => 'danger',
            );

            $line["_status"] = $line["user"]["status"];
            $line["user"]["status"] = sprintf('<span class="label label-%s">%s</span>', $status[$line["_status"]], $line["_status"]);

            return $line;
        };

        return $formatter;
    }*/
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->features->setFeatures(array(
            "ordering" => false,
            "paging" => true,
            "processing" => true,
            "searching" => true,
        ));
        $this->ajax->setOptions(array(
            "url" => $this->container->get("router")->generate('event_group_post_results'),
            "type" => "GET"
        ));

        $this->options->setOptions(array(
            "length_menu" => array(10, 25, 50, 100),
            "order_classes" => true,
            "order_multi" => false,
            "page_length" => 10,
            "paging_type" => Style::FULL_NUMBERS_PAGINATION,
            "responsive" => false,
            "class" => 'table table-bordered table-striped table-condensed flip-content groups-list',
            "individual_filtering" => false,
            "use_integration_options" => true
        ));
        $this->columnBuilder
            ->add("name", "column", array(
                "class" => "",
                "title" => "Name",
            ))
            ->add(null, "action", array(
                "title" => "Action",
                "actions" => array(
                    array(
                        "route" => "event_group_edit",
                        "label" => "Event Group Edit",
                        "route_parameters" => array(
                            "id" => "id"
                        ),
                        "attributes" => array(
                            "class" => " btn purple btn-sm",
                            "role" => "button"
                        ),

                    ),
                    array(
                        "route" => "event_group_delete",
                        "label" => "Group Delete",
                        "route_parameters" => array(
                            "id" => "id"
                        ),
                        "attributes" => array(
                            "class" => "btn red btn-sm",
                            "role" => "button"
                        ),
                    )

                )
            ));

    }
    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return "EventBundle:EventGroup";
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "eventGroup_datatable";
    }
}