<?php
namespace VendorBundle\Datatables;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
/**
 * Class VendorDatatable
 *
 * @package VendorBundle/Datatables
 */
class VendorDatatable extends AbstractDatatableView
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

            $line["_status"] = $line["user"]["status"];
            $line["user"]["status"] = sprintf('<span class="label label-%s">%s</span>', $status[$line["_status"]], $line["_status"]);

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
            "ordering" => false,
            "paging" => true,
            "processing" => true,
            "searching" => true,
        ));
        $this->ajax->setOptions(array(
            "url" => $this->container->get("router")->generate('vendor_post_results'),
            "type" => "GET"
        ));

        $this->options->setOptions(array(
            "length_menu" => array(10, 25, 50, 100),
            "order_classes" => true,
            "order_multi" => false,
            "page_length" => 10,
            "paging_type" => Style::FULL_NUMBERS_PAGINATION,
            "responsive" => false,
            "class" => 'table table-striped table-bordered table-hover vendors-list',
            "individual_filtering" => false,
            "use_integration_options" => true
        ));
        $this->columnBuilder
            ->add("user.firstname", "column", array(
                "class" => "",
                "title" => "First Name",
            ))
            ->add("user.lastname", "column", array(
                "class" => "",
                "title" => "Last Name",
            ))
            ->add("user.email", "column", array(
                "class" => "",
                "title" => "Email",
            ))
            ->add("companyName", "column", array(
                "class" => "",
                "title" => "Company Name",
            ))
            ->add("description", "column", array(
                "class" => "",
                "title" => "Description",
            ))
            ->add("address", "column", array(
                "class" => "",
                "title" => "Address",
            ))
            ->add("user.status", "column", array(
                "class" => "user-status",
                "title" => "Status",
            ))
            ->add(null, "action", array(
                "title" => "Action",
                "actions" => array(
                    array(
                        "route" => "vendor_edit",
                        "label" => "Vendor Edit",
                        "route_parameters" => array(
                            "id" => "user.id"
                        ),
                        "attributes" => array(
                            "class" => " btn purple btn-sm",
                            "role" => "button"
                        ),

                    ),
                    array(
                        "route" => "users_changeStatus",
                        "label" => "Change Status",
                        "route_parameters" => array(
                            "current" => "_status",
                            "id" => "user.id"
                        ),
                        "attributes" => array(
                            "class" => "changeStatus btn blue btn-sm",
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
        return "VendorBundle:VendorProfile";
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "vendor_datatable";
    }
}