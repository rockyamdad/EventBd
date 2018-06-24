<?php

namespace EventBundle\Controller;

use Doctrine\Common\Util\Debug;
use EventBundle\Entity\Event;
use EventBundle\Entity\Ticket;
use EventBundle\Form\TicketType;
use JMS\SecurityExtraBundle\Annotation as JMS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TicketController extends Controller
{

    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request)
    {
//        var_dump($request->re)
        $ticket = new Ticket();
        $form = $this->createForm(new TicketType(), $ticket);
        $form->handleRequest($request);
        $event = $this->getDoctrine()->getRepository('EventBundle:Event')
            ->find($ticket->getEventId());
        $ticket->setEvent($event);

        if ($form->isValid()) {
            $ticket->setEvent($event);
            $this->getDoctrine()->getRepository('EventBundle:Ticket')->create($ticket);
            $event->setIsCompleted(1);
            $this->getDoctrine()->getRepository('EventBundle:Event')->create($event);

            $ticketInfo = $this->getDoctrine()->getRepository('EventBundle:Ticket')
                ->find($ticket->getId());
            $list = $this->ticketInfoConvertToArray($ticketInfo);
            return new JsonResponse($list);
        }
    }

    public function ticketInfoConvertToArray($ticketInfo){

        $array = array();

        $array['ticketId'] = $ticketInfo->getId();
        $array['ticketType'] = $ticketInfo->getTicketCategory()->getName();
        $array['ticketName'] = $ticketInfo->getName();
        $array['quantity'] = $ticketInfo->getQuantity();
        $array['price'] = $ticketInfo->getPrice();
        $array['tax'] = $ticketInfo->getTax();
        $array['maximumTicketBuy'] = $ticketInfo->getMaximumTicketBuy();
        $array['EventId'] = $ticketInfo->getEvent()->getId();

        return $array;
    }

    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function deleteAction(Ticket $ticket)
    {

        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('EventBundle:Ticket')->find($ticket->getId());

        if (!$ticket) {
            throw $this->createNotFoundException('No guest found for id '.$ticket->getId());
        }
        $em->getRepository('EventBundle:Ticket')->delete($ticket);

        $message = array('Ticket  Successfully Deleted');
        return new JsonResponse($message);
    }



}
