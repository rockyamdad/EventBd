<?php
namespace UserBundle\Datatables;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
/**
 * Class UserDatatable
 *
 * @package UserBundle/Datatables
 */
class UserDatatable extends AbstractDatatableView
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
            $line["roles"] = in_array("ROLE_ADMIN", $line["roles"]) ? 'Admin' : 'User';
            $line["_status"] = $line["status"];
            $line["_action"] = $this->getActionLabel($line["status"]);
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
            "url" => $this->container->get("router")->generate('post_results'),
            "type" => "GET"
        ));

        $this->options->setOptions(array(
            "length_menu" => array(10, 25, 50, 100),
            "order_classes" => false,
           // "order" => array("column" => 0, "direction" => "asc"),
            "order_multi" => true,
            "page_length" => 10,
            "paging_type" => Style::FULL_NUMBERS_PAGINATION,
            "responsive" => false,
            "class" => 'table table-bordered table-striped table-condensed flip-content users-list',
            "individual_filtering" => false,
            "use_integration_options" => true
        ));
        $this->columnBuilder
            ->add("firstname", "column", array(
                "class" => "",
                "title" => "First Name",
            ))
            ->add("lastname", "column", array(
                "class" => "",
                "title" => "Last Name",
                'searchable' => false,
                'orderable' => false,
            ))
            ->add("email", "column", array(
                "class" => "",
                "title" => "Email",
            ))
            ->add("gender", "column", array(
                "class" => "",
                "title" => "Gender",
            ))
            ->add("roles", "column", array(
                "class" => "",
                "title" => "Role",
            ))
            ->add("cellphone", "column", array(
                "class" => "",
                "title" => "Phone",
            ))
            ->add("status", "column", array(
                "class" => "user-status",
                "title" => "Status",
             ))
            ->add(null, "action", array(
                "title" => "Action",
                "actions" => array(
                    array(
                        "route" => "users_edit",
                        "label" => "Edit",
                        "route_parameters" => array(
                            "id" => "id"
                        ),
                        "attributes" => array(
                            "class" => "fa fa-edit btn purple btn-sm",
                            "role" => "button"
                        ),
                        "role" => "ROLE_USER"
                    ),
                    array(
                        "route" => "users_changeStatus",
                        "label" => "Change Status",
                        "route_parameters" => array(
                            "current" => "_status",
                            "id" => "id"
                        ),
                        "attributes" => array(
                            "class" => "fa fa-link changeStatus btn blue btn-sm",
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
        return "UserBundle:User";
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "user_datatable";
    }

    private function getActionLabel($status)
    {
        return $status;
    }
}