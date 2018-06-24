<?php

namespace EventBundle\Controller;

use Doctrine\Common\Util\Debug;
use EventBundle\Entity\CartItem;
use EventBundle\Entity\Event;
use EventBundle\Entity\EventView;
use EventBundle\Entity\Location;
use EventBundle\Entity\News;
use EventBundle\Entity\EventGroup;
use EventBundle\Entity\ScheduleDetail;
use EventBundle\Entity\ScheduleMaster;
use EventBundle\Entity\Speaker;
use EventBundle\Entity\Sponsor;
use EventBundle\Entity\Ticket;
use EventBundle\Form\EventType;
use EventBundle\Form\LocationType;
use EventBundle\Form\NewsType;
use EventBundle\Form\ScheduleMasterType;
use EventBundle\Form\SpeakerType;
use EventBundle\Form\SponsorType;
use EventBundle\Form\TicketType;
use JMS\SecurityExtraBundle\Annotation as JMS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;


class EventController extends Controller
{
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function indexAction()
    {
        $eventDatatable = $this->get("sg_datatables.event");

        return $this->render('EventBundle:Events:list.html.twig',array(
            'datatable' => $eventDatatable
        ));
    }

    public function indexResultsAction()
    {
        /**
         * @var \Sg\DatatablesBundle\Datatable\Data\DatatableData $datatable
         */
        $datatable = $this->get("sg_datatables.datatable")->getDatatable($this->get("sg_datatables.event"));

        return $datatable->getResponse();
    }

    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request)
    {

        if (!empty($request->request->all()['eventbundle_event']['id'])){
            $event = $this->getDoctrine()->getRepository('EventBundle:Event')
                                         ->find($request->request->all()['eventbundle_event']['id']);
        } else {
            $event    = new Event();
        }

        $schedule = new ScheduleMaster();
        $sponsor  = new Sponsor();
        $news     = new News();
        $speaker  = new Speaker();
        $location = new Location();
        $ticket   = new Ticket();
        $form = $this->createForm(new EventType(false),$event);
        $scheduleForm = $this->createForm(new ScheduleMasterType(false, $request),$schedule);
        $scheduleMultiple = $this->createForm(new ScheduleMasterType(true, $request),$schedule);
        $formSetting = $this->createForm(new EventType(true),$event);
        $locationForm = $this->createForm(new LocationType(),$location);
        $ticketForm = $this->createForm(new TicketType(),$ticket);
        $formSpeaker = $this->createForm(new SpeakerType(),$speaker);
        $sponsorForm = $this->createForm(new SponsorType(),$sponsor);
        $newsForm = $this->createForm(new NewsType(),$news);


        if($request->getMethod()=="POST")
        {
            $form->handleRequest($request);
            if($form->isValid()) {
                /** @var UploadedFile $file */
                $file = $request->files->all()['eventbundle_event']['file'];

                if(!empty($file)){

                    $uploaded = $file->move($event->getUploadRootDir(), $file->getClientOriginalName());

                    if ($uploaded) {
                        $event->setPath($uploaded->getFilename());
                    }
                }
                $event->setCreatedDateTime(new \DateTime());
                $event->setUser($this->getUser());
                $event->setStatus('Activate');
                $this->getDoctrine()->getRepository('EventBundle:Event')->create($event);
                $response = new Response(json_encode($event->getId()));
                return $response;
            }
        }

        return $this->render('EventBundle:Events:add.html.twig',array(
            'form' => $form->createView(),
            'locationForm'=>$locationForm->createView(),
            'scheduleForm' => $scheduleForm->createView(),
            'scheduleMultiple' => $scheduleMultiple->createView(),
            'ticketForm'=>$ticketForm->createView(),
            'formSetting'=>$formSetting->createView(),
            'formSponsor'=>$sponsorForm->createView(),
            'formNews'=>$newsForm->createView(),
            'formSpeaker'=> $formSpeaker->createView(),
            'path'=>$event->getPath(),
            'ticketList'=>'',
            'speakerList'=>'',
            'newsList'=>''

        ));
    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function editAction(Request $request, Event $event)
    {

        $em = $this->getDoctrine()->getManager();

        $location = $this->getDoctrine()->getRepository('EventBundle:Location')
            ->findByEvent($event->getId());

        $schedule = $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')
            ->findByEvent($event->getId());

        $multipleSchedules = $em->getRepository('EventBundle:ScheduleMaster')->findByEvent($event->getId());
        $multipleSchedulesList = $this->scheduleInfoConvertToArray($multipleSchedules);
        $ticketList = $em->getRepository('EventBundle:Ticket')->findByEvent($event->getId());
        $speakerList = $em->getRepository('EventBundle:Speaker')->findByEvent($event->getId());
        $newsList = $em->getRepository('EventBundle:News')->findByEvent($event->getId());
        $sponsorsList = $em->getRepository('EventBundle:Sponsor')->findByEvent($event->getId());


        $ticketArray = $this->ticketDetailInfo($ticketList);
        $speakerArray = $this->speakerDetailInfo($speakerList);
        $newsArray = $this->newsDetailInfo($newsList);

        $sponsor  = new Sponsor();
        $news     = new News();
        $speaker  = new Speaker();
        $ticket = new Ticket();
        $multipleSchedule  = new ScheduleMaster();
        $form = $this->createForm(new EventType(false),$event);
        $formSetting = $this->createForm(new EventType(true),$event);
        $scheduleForm = $this->createForm(new ScheduleMasterType(false, $request),$schedule[0]);
        $scheduleMultiple = $this->createForm(new ScheduleMasterType(true, $request),$multipleSchedule);
        $locationForm = $this->createForm(new LocationType(),$location[0]);
        $ticketForm = $this->createForm(new TicketType(),$ticket);
        $formSpeaker = $this->createForm(new SpeakerType(),$speaker);
        $sponsorForm = $this->createForm(new SponsorType(),$sponsor);
        $newsForm = $this->createForm(new NewsType(),$news);

        return $this->render('EventBundle:Events:add.html.twig',array(
            'form' => $form->createView(),
            'locationForm'=>$locationForm->createView(),
            'scheduleForm' => $scheduleForm->createView(),
            'scheduleMultiple' => $scheduleMultiple->createView(),
            'ticketForm'=>$ticketForm->createView(),
            'formSetting'=>$formSetting->createView(),
            'formSponsor'=>$sponsorForm->createView(),
            'formNews'=>$newsForm->createView(),
            'formSpeaker'=> $formSpeaker->createView(),
            'ticketList'=>$ticketArray,
            'speakerList'=>$speakerArray,
            'sponsorsList'=>$sponsorsList,
            'newsList'=>$newsArray,
            'multipleSchedules'=>$multipleSchedulesList
        ));
    }
    public function scheduleInfoConvertToArray($scheduleInfo){

        $scheduleArray = array();
        foreach($scheduleInfo as $key => $schedule){
            $scheduleCount = $this->getDoctrine()->getRepository('EventBundle:ScheduleDetail')
                ->findByMaster($schedule->getId());

            $scheduleArray[$key]['scheduleId'] = $schedule->getId();
            $scheduleArray[$key]['frequency'] =  $schedule->getFrequency();
            $days = Count($scheduleCount);
            $count = $days.' Dates';
            $scheduleArray[$key]['day'] = $count;
            $scheduleArray[$key]['startDate'] = date_format($schedule->getStartDate(), ' jS F Y');
            $scheduleArray[$key]['startTime'] = date_format($schedule->getStartTime(), 'g:iA');
            $scheduleArray[$key]['endDate']   = date_format($schedule->getEndDate(), ' jS F Y');
            $scheduleArray[$key]['endTime']   = date_format($schedule->getEndTime(), 'g:iA');
            $scheduleArray[$key]['eventId']   = $schedule->getEvent()->getId();
        }

        return $scheduleArray;
    }
    public function eventsByCategoryAction(EventGroup $group)
    {
        $allCategories = $this->getDoctrine()->getRepository('EventBundle:EventGroup')->findAll();
        $allEventByCategory =  $this->getDoctrine()->getRepository('EventBundle:Event')->findBy(array('group'=>$group,'status'=>'Activate','isCompleted'=>1));
        $price =  $this->getDoctrine()->getRepository('EventBundle:Ticket')->getEventsMinimumPriceByEventGroup($group);
        return $this->render('EventBundle:Frontend:events_by_category.html.twig',array(
            'eventsByCategory' => $allEventByCategory,
            'allCategories'   => $allCategories,
            'price'   => $price
        ));

    }

    public function eventByIdAction(Event $event)
    {
        $event =  $this->getDoctrine()->getRepository('EventBundle:Event')->find($event);
        return $this->render('EventBundle:Frontend:events_by_id.html.twig',array(
            'event' => $event
        ));

    }
    public function vendorSingleEventAction(Event $event)
    {

        $famousEvents =  $this->getDoctrine()->getRepository('EventBundle:Event')->getFamousEvents();
        $allCategories = $this->getDoctrine()->getRepository('EventBundle:EventGroup')->findAll();
        $scheduleDates = array();

        /** @var ScheduleDetail $schedule */
        foreach($event->getScheduleDetail() as $schedule)
        {
            $row['start'] = $schedule->getScheduleDate()->format('Y-m-d');
            $row['description'] = '';
            $row['imageurl'] = '../../assets/frontend/layout/img/icons/ev.png';
            $scheduleDates[] = $row;
        }


        return $this->render('EventBundle:Frontend:singleEvent.html.twig',array(
            'event' => $event,
            'allCategories'   => $allCategories,
            'famousEvents'   => $famousEvents,
            'scheduleDates'=> $scheduleDates

        ));

    }

    public function allEventAction()
    {
        $events =  $this->getDoctrine()->getRepository('EventBundle:Event')->findBy(array('status'=>'Activate'));
        return $this->render('EventBundle:Frontend:vendor_events.html.twig',array(
            'events' => $events
        ));

    }
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Event $events)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('EventBundle:Event')->find($events->getId());

        if (!$event) {
            throw $this->createNotFoundException('No guest found for id '.$events->getId());
        }
        $em->getRepository('EventBundle:Event')->delete($event);

        $this->get('session')->getFlashBag()->add(
            'message',
            'Event  Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('event_list'));
    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function eventsByUserAction()
    {
        $id = $this->getUser();
        $allCategories = $this->getDoctrine()->getRepository('EventBundle:EventGroup')->findAll();
        $allEventsByUser =  $this->getDoctrine()->getRepository('EventBundle:Event')->findBy(array('user'=>$id,'status'=>'Activate'));
        $price =  $this->getDoctrine()->getRepository('EventBundle:Ticket')->getEventsMinimumPrice();

        return $this->render('EventBundle:Frontend:events_by_user.html.twig',array(
            'eventsByUser' => $allEventsByUser,
            'allCategories'   => $allCategories,
            'price'   => $price
        ));

    }
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function changeStatusAction($current, Event $event)
    {
        if($event->getStatus() == $current) {
            $event->setStatus($current == 'Activate' ? 'Deactivate': 'Activate');

            $this->getDoctrine()->getManager()->getRepository('EventBundle:Event')->update($event);
        }
        return new JsonResponse(array(
            'id' => $event->getId(),
            'status' => $event->getStatus()
        ));
    }

    /**
     * @param $ticketList
     * @return array
     */
    private function ticketDetailInfo($ticketList)
    {
        $ticketArray = array();
        foreach ($ticketList as $key => $ticketData) {

            $ticketArray[$key]['ticketId']         = $ticketData->getId();
            $ticketArray[$key]['ticketType']       = $ticketData->getTicketCategory()->getName();
            $ticketArray[$key]['ticketName']       = $ticketData->getName();
            $ticketArray[$key]['quantity']         = $ticketData->getQuantity();
            $ticketArray[$key]['tax']              = $ticketData->getTax();
            $ticketArray[$key]['price']            = $ticketData->getPrice();
            $ticketArray[$key]['maximumTicketBuy'] = $ticketData->getMaximumTicketBuy();
        }

        return $ticketArray;
    }

    private function speakerDetailInfo($speakerList)
    {
        $speakerArray = array();
        foreach ($speakerList as $key => $speakerData) {

            $speakerArray[$key]['id']           = $speakerData->getId();
            $speakerArray[$key]['firstName']    = $speakerData->getFirstName();
            $speakerArray[$key]['lastName']     = $speakerData->getLastName();
            $speakerArray[$key]['description']  = $speakerData->getdescription();
            $speakerArray[$key]['path']         = $speakerData->getPath();


        }
        return $speakerArray;
    }
    private function newsDetailInfo($newsList)
    {
        $newsArray = array();
        foreach ($newsList as $key => $newsData) {

            $newsArray[$key]['id']     = $newsData->getId();
            $newsArray[$key]['title']  = $newsData->getTitle();
            $newsArray[$key]['details'] = $newsData->getDetails();
            $newsArray[$key]['type']   = $newsData->getType();


        }
        return $newsArray;
    }
    public function viewsCalculateAction(Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $eventViews = $em->getRepository('EventBundle:EventView')->findOneByEvent($event);
        if($eventViews)
        {
            $view = $eventViews->getViews()+1;
            $eventViews->setViews($view);
            $this->getDoctrine()->getRepository('EventBundle:EventView')->update($eventViews);
        }else{
            $eventViews = new EventView();
            $eventViews->setEvent($event);
            $eventViews->setViews(1);
            $this->getDoctrine()->getRepository('EventBundle:EventView')->create($eventViews);
        }
        return new JsonResponse();
    }
    public function scheduleTicketSearchAction(Event $event,Request $request)
    {
        $date = $request->request->get('data');
        $events =  $this->getDoctrine()->getRepository('EventBundle:ScheduleDetail')->getAllByEventDate($event, new \DateTime($date));
        $list = $this->scheduleConvertToArray($events);
        return new JsonResponse($list);
    }

    public function addEventToShoppingCartAction(Request $request)
    {
        $session = $request->getSession();
        $eventRequest = $request->request->get('data');
        $events = $session->get('events', array());

        $eventIndex = $eventRequest[8] . "_" . $eventRequest[9];

        if(isset($events[$eventIndex])){
            $eventRequest[4] += $events[$eventIndex][4];
        }

        $events[$eventIndex] = $eventRequest;

        $session->set('events', $events);

        $countCartEvents = $this->getCartTicketInfo($request);
        return new JsonResponse($countCartEvents);
    }
    public function scheduleConvertToArray($events){

        $data = array();

            foreach($events as $key=>$detail)
        {
            /** @var ScheduleDetail $detail */
            $data[$key]['id'] = $detail->getId();
            $data[$key]['date'] = $detail->getScheduleDate()->format('j F Y');
            $data[$key]['startTime'] = $detail->getStartTime()->format('g:iA');
            $data[$key]['endTime'] = $detail->getEndTime()->format('g:iA');
            $data[$key]['tickets'] = $detail->getEvent()->getTicket();
            /*foreach($detail->getEvent()->getTicket() as $key=>$ticket)
            {
                $data[$key]['ticketName'] = $ticket->getName();
                $data[$key]['price'] = $ticket->getPrice();
            }*/

        }

        return $data;


    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function showCartAction(Request $request)
    {
        $session = $request->getSession();
        $cartEvents = $session->get('events');

        return $this->render('EventBundle:Frontend:cart.html.twig',array(
            'event' => $cartEvents

        ));

    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function deleteShoppingCartEventAction(Request $request)
    {
        $session = $request->getSession();
        $event = $request->request->get('data');
        $allEvents = $session->get('events');
        unset($allEvents[$event]);
        $session->set('events', $allEvents);
        $countCartEvents = $this->getCartTicketInfo($request);
        return new JsonResponse($countCartEvents);
    }
    public function summaryCartAction(Request $request)
    {
        $session = $request->getSession();
        $cartEvents = $session->get('events');

        return $this->render('EventBundle:Frontend:cartSummary.html.twig',array(
            'event' => $cartEvents

        ));

    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function checkoutAction(Request $request)
    {
        $session = $request->getSession();
        $cartEvents = $session->get('events');
        return $this->render('EventBundle:Frontend:checkout.html.twig',array(
            'event' => $cartEvents

        ));

    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function confirmOrderAction(Request $request)
    {
        $session = $request->getSession();
        $cartEvents = $session->get('events');
        $userFirstName = $this->getUser()->getFirstName();
        $userLastName = $this->getUser()->getLastName();
        $userName = $userFirstName.' '.$userLastName;
        foreach($cartEvents as $cartEvent){
            $cartItems = new CartItem();
            $cartItems->setEventName($cartEvent[6]);
            $cartItems->setScheduleDate($cartEvent[0]);
            $cartItems->setScheduleTime($cartEvent[1]);
            $cartItems->setQuantity($cartEvent[4]);
            $cartItems->setPrice($cartEvent[3]);
            $cartItems->setTicketName($cartEvent[2]);
            $cartItems->setUserName($userName);
            $cartItems->setUser($this->getUser());
            $cartItems->setBuyDate(new \DateTime());
            $this->getDoctrine()->getRepository('EventBundle:CartItem')->create($cartItems);
        }
        $this->get('session')->clear();
        return $this->redirect($this->generateUrl('events_homepage'));
    }
    public function cartTicketInfoAction(Request $request)
    {
        $quantityPrice = $this->getCartTicketInfo($request);
        return $this->render('EventBundle:Frontend:header_cart_info.html.twig',array(
            'quantityPrice' => $quantityPrice
        ));

    }
    private function getCartTicketInfo($request)
    {
        $session = $request->getSession();
        $cartEvents = $session->get('events');
        $totalQuantity = 0;
        $totalPrice = 0;
        $quantityPrice = array();
        if($cartEvents){
            foreach($cartEvents as $cartEvent){
                $totalQuantity = $totalQuantity + $cartEvent[4];
                $totalPrice = $totalPrice + (($cartEvent[3] ? $cartEvent[3] : 0) * $cartEvent[4]);
            }
        }
        $quantityPrice['quantity'] = $totalQuantity;
        $quantityPrice['price'] = $totalPrice;

        return $quantityPrice;
    }
}
