<?php
namespace EventBundle\Datatables;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
/**
 * Class EventDatatable
 *
 * @package EventBundle/Datatables
 */
class EventDatatable extends AbstractDatatableView
{

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatter = function($line) {
            $status = array(
                'Activate' => 'success',
                'Deactivate' => 'danger',
            );
            $line["scheduleMaster"]["startDate"] = $line['scheduleMaster']['startDate']->format('Y-m-d');
            $line["scheduleMaster"]["endDate"] =  $line['scheduleMaster']['endDate']->format('Y-m-d');
            $line["_status"] = $line["status"];

            $line["status"] = sprintf('<span class="label label-%s">%s</span>', $status[$line["status"]], $line["status"]);



            return $line;
        };

        return $formatter;
    }
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->features->setFeatures(array(
            "ordering" => true,
            "paging" => true,
            "processing" => true,
            "searching" => true,
        ));
        $this->ajax->setOptions(array(
            "url" => $this->container->get("router")->generate('event_post_results'),
            "type" => "GET"
        ));

        $this->options->setOptions(array(
            "length_menu" => array(10, 25, 50, 100),
            "order_classes" => true,
            "order_multi" => false,
            "page_length" => 10,
            "paging_type" => Style::FULL_NUMBERS_PAGINATION,
            "responsive" => true,
            "class" => 'table table-bordered table-striped table-condensed flip-content events-list',
            "individual_filtering" => false,
            "use_integration_options" => true
        ));
        $this->columnBuilder
            ->add("name", "column", array(
                "class" => "",
                "title" => "Name",
            ))
            ->add("group.name", "column", array(
                "class" => "",
                "title" => "Group",
            ))
            ->add("location.venueName", "column", array(
                "class" => "",
                "title" => "Venue",
            ))
            ->add("location.address", "column", array(
                "class" => "",
                "title" => "Address",
            ))

            ->add("scheduleMaster.startDate", "column", array(
                 "class" => "",
                 "title" => "Start Time",
             ))
             ->add("scheduleMaster.endDate", "column", array(
                 "class" => "",
                 "title" => "End Time",
             ))
         /*   ->add("description", "column", array(
                "class" => "",
                "title" => "Description",
            ))*/
            ->add("contact", "column", array(
                "class" => "",
                "title" => "Contact",
            ))
            ->add("privacy", "column", array(
                "class" => "",
                "title" => "Privacy",
            ))
            ->add("tags", "column", array(
                "class" => "",
                "title" => "Tags",
            ))
          ->add("status", "column", array(
              "class" => "event-status",
              "title" => "Status",
          ))
            ->add(null, "action", array(
                "title" => "Action",
                "actions" => array(
                /*    array(
                        "route" => "event_edit",
                        "label" => "Edit",
                        "route_parameters" => array(
                            "id" => "id"
                        ),
                        "attributes" => array(
                            "class" => "fa fa-edit btn purple btn-sm",
                            "role" => "button"
                        ),
                        "role" => "ROLE_USER"
                    ),*/
                    array(
                        "route" => "events_changeStatus",
                        "label" => "Change Status",
                        "route_parameters" => array(
                            "current" => "_status",
                            "id" => "id"
                        ),
                        "attributes" => array(
                            "class" => "fa fa-link changeStatus  btn purple btn-sm",
                            "role" => "button"
                        ),
                        "role" => "ROLE_USER"
                    )

                )
            ));

    }
    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return "EventBundle:Event";
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "event_datatable";
    }

    private function getActionLabel($status)
    {
        return $status;
    }
}