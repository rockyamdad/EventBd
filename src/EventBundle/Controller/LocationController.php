<?php

namespace EventBundle\Controller;


use EventBundle\Entity\Event;
use EventBundle\Entity\Location;
use EventBundle\Form\LocationType;
use JMS\SecurityExtraBundle\Annotation as JMS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LocationController extends Controller
{

    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request)
    {

        /** @var Event $event */
        $event = $this->getDoctrine()->getRepository('EventBundle:Event')
                ->find($request->request->all()['eventbundle_location']['eventId']);
        $location = $event->getLocation();
        if(!$location){
            $location = new Location();
            $event = $this->getDoctrine()->getRepository('EventBundle:Event')
                ->find($event);
            $location->setEvent($event);
        }

        $form = $this->createForm(new LocationType(), $location);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->getDoctrine()->getRepository('EventBundle:Location')->create($location);
            $singleOrMultipleSchedule = $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')
                ->findOneByEvent($event);
            //var_dump($singleOrMultipleSchedule->getFrequency());
            $idList = array(
                'locationId' => $location->getId(),
                'isMultiple' => $singleOrMultipleSchedule ? $singleOrMultipleSchedule->getFrequency():''
            );
            $response = new Response(json_encode($idList));
            return $response;
        }
    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function updateAction(Request $request ,Location $location)
    {
        $this->getDoctrine()->getRepository('EventBundle:Location')->update($location);


        return new Response("updated");

    }


}