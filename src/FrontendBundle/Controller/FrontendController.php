<?php

namespace FrontendBundle\Controller;
use EventBundle\Entity\Event;
use EventBundle\Form\EventSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{
    public function indexAction(Request $request)
    {

        $searchPage = 'homePageSearch';
        $form = $this->eventSearch($request,$searchPage);
        $sliderDocuments = $this->getDoctrine()->getRepository('EventBundle:Document')->getAll();
        $newEvents =  $this->getDoctrine()->getManager()->getRepository('EventBundle:Event')->getNewEvents();
        $poplarEvents =  $this->getDoctrine()->getManager()->getRepository('EventBundle:Event')->getPopularEvents();
        $upComingEvents =  $this->getDoctrine()->getManager()->getRepository('EventBundle:Event')->getUpComingEvents();
        $price =  $this->getDoctrine()->getRepository('EventBundle:Ticket')->getEventsMinimumPrice();
        //var_dump($poplarEvents);exit;
        return $this->render('FrontendBundle:Default:index.html.twig',array(
            'form'=>$form->createView(),
            'newEvents'=>$newEvents,
            'poplarEvents'=>$poplarEvents,
            'upComingEvents'=>$upComingEvents,
            'sliderPhotos'=>$sliderDocuments,
            'price'=>$price
        ));
    }

    public function productAction()
    {
        return $this->render('FrontendBundle:Default:product.html.twig');
    }

    public function detailsAction()
    {
        return $this->render('FrontendBundle:Default:details.html.twig');
    }

    public function searchAction()
    {
        return $this->render('FrontendBundle:Default:search.html.twig');
    }

    public function viewCartAction()
    {
        return $this->render('FrontendBundle:Default:cart.html.twig');
    }

    public function checkoutAction()
    {
        return $this->render('FrontendBundle:Default:checkout.html.twig');
    }

    public function eventAction()
    {
        return $this->render('FrontendBundle:Default:event.html.twig');
    }
    public function myAccountAction()
    {
        return $this->render('FrontendBundle:Default:myAccount.html.twig');
    }
    public function contactAction()
    {
        return $this->render('FrontendBundle:Default:contactUs.html.twig');
    }
    public function termsConditionAction()
    {
        return $this->render('FrontendBundle:Default:termsCondition.html.twig');
    }
    public function eventsAction()
    {
        return $this->render('FrontendBundle:Default:events.html.twig');
    }
    public function eventSearchForm($request,$page)
    {

        $form = new EventSearchType($page);
        $data = $request->get($form->getName());
        return array($form, $data);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\Form
     */
    private function eventSearch(Request $request,$page)
    {
        list($form, $data) = $this->eventSearchForm($request,$page);
        $form = $this->createForm($form);
        $form->submit($data);

        return $form;
    }

    public function searchEventAction(Request $request){

        $searchPage = 'searchPageSearch';
        $data = $request->request->get('search');

        $form = $this->eventSearch($request,$searchPage);

        $eventSearch = $this->getDoctrine()->getManager()->getRepository('EventBundle:Event')->getEventSearchResult($data);
        $eventLat = $this->getDoctrine()->getManager()->getRepository('EventBundle:Event')->getLatLang($eventSearch);
        return $this->render('FrontendBundle:Search:searchEvent.html.twig',array(
            'form'=>$form->createView(),
            'eventSearchResults'=>$eventSearch,
            'eventLat'=>$eventLat
        ));
    }

}
