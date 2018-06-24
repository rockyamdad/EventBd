<?php
namespace CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
    {
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'page-sidebar-menu page-sidebar-menu-hover-submenu',
            'data-keep-expanded' => 'false',
            'data-auto-scroll' => 'true',
            'data-slide-speed' => '200',
        ));

        $menu  = $this->singleMenu($menu);
        $menu  = $this->usersMenu($menu);
      /*  $menu  = $this->vendorsMenu($menu);*/
        $menu  = $this->eventsMenu($menu);
        $menu  = $this->groupMenu($menu);

        return $menu;
    }

    public function singleMenu($menu)
    {
        $menu
            ->addChild('Home',array('route' => 'events_homepage'))
            ->setAttribute('icon','fa fa-home')
        ;
        $menu
            ->addChild('Dashboard', array('route' => 'event_dashboard'))
            ->setAttribute('icon', 'fa fa-dashboard');
        $menu
            ->addChild('Document', array('route' => 'document_list'))
            ->setAttribute('icon', 'fa fa-calendar');


        return $menu;
    }
    public function usersMenu($menu){
        $menu
            ->addChild('User')
            ->setAttribute('icon','fa fa-user')
            ->setAttribute('dropdown', true);

        $menu['User']->addChild('Users List', array('route' => 'users_list'));
        $menu['User']->addChild('Add User', array('route' => 'users_add'));

        return $menu;
    }
  /*  public function vendorsMenu($menu){
        $menu
            ->addChild('Vendor')
            ->setAttribute('icon','fa fa-users')
            ->setAttribute('dropdown', true);
        $menu['Vendor']->addChild('Vendor List', array('route' => 'vendor_list'));
        $menu['Vendor']->addChild('Add Vendor', array('route' => 'vendor_add'));
        $menu['Vendor']->addChild('DeActive Vendors', array('route' => 'users_deactive'));

        return $menu;
    }*/
    public function eventsMenu($menu){
        $menu
            ->addChild('Event')
            ->setAttribute('icon','fa fa-calendar')
            ->setAttribute('dropdown', true);

        $menu['Event']->addChild('Event List', array('route' => 'event_list'));
        $menu['Event']->addChild('Add Event', array('route' => 'users_add'));

        return $menu;
    }
    public function groupMenu($menu){
        $menu
            ->addChild('Group')
            ->setAttribute('icon','fa fa-calendar')
            ->setAttribute('dropdown', true);

        $menu['Group']->addChild('Event Type List', array('route' => 'event_group_list'));
        $menu['Group']->addChild('Add Event Type', array('route' => 'event_group_create'));

        return $menu;
    }
    public function get($id)
    {
        return $this->container->get($id);
    }
}