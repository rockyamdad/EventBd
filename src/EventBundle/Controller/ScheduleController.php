<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Event;
use EventBundle\Entity\ScheduleDetail;
use EventBundle\Entity\ScheduleMaster;
use EventBundle\Form\ScheduleMasterType;
use JMS\SecurityExtraBundle\Annotation as JMS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\DateTime;


class ScheduleController extends Controller
{

    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request)
    {
        /** @var Event $event */
        $event = $this->getDoctrine()->getRepository('EventBundle:Event')
            ->find($request->request->all()['eventbundle_schedule_master']['eventId']);
        $scheduleMaster = $event->getScheduleMaster()[0];
        if(!$scheduleMaster){
            $scheduleMaster = new ScheduleMaster();
            $event = $this->getDoctrine()->getRepository('EventBundle:Event')
                ->find($event);
            $scheduleMaster->setEvent($event);

        }else{
          $scheduleDetails = $this->getDoctrine()->getRepository('EventBundle:ScheduleDetail')
                ->findOneByMaster($scheduleMaster);
            $em = $this->getDoctrine()->getManager();
            $em->getRepository('EventBundle:ScheduleDetail')->delete($scheduleDetails);
        }
        $scheduleDetails = new ScheduleDetail();
        $form = $this->createForm(new ScheduleMasterType(false, $request), $scheduleMaster);

        $form->handleRequest($request);


        if ($form->isValid()) {




            $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')->create($scheduleMaster);

           $scheduleDetails->setStartTime($scheduleMaster->getStartTime());
            $scheduleDetails->setEndTime($scheduleMaster->getEndTime());
            $scheduleDetails->setScheduleDate($scheduleMaster->getStartDate());
            $scheduleDetails->setEvent($event);
            $schedule = $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')
                ->find($scheduleMaster->getId());
            $scheduleDetails->setMaster($schedule);

            $this->getDoctrine()->getRepository('EventBundle:ScheduleDetail')->create($scheduleDetails);
            $idList = array(
                'scheduleId'=>$scheduleMaster->getId(),
               // 'eventId'=>$scheduleMaster->getEventId()
            );
            $response = new JsonResponse($idList);
            return $response;
        }
    }
    public function sponsorInfoConvertToArray($sponsorInfo){

        $array = array();

        $array['id'] = $sponsorInfo->getId();
        $array['name'] = $sponsorInfo->getName();
        $array['description'] = $sponsorInfo->getDescription();
        $array['photo'] = $sponsorInfo->getPath();

        return $array;
    }

    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function createMultipleScheduleAction(Request $request)
    {
        if ($request->request->get('multipleScheduleId')){
            $scheduleMaster = $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')
                ->find($request->request->get('multipleScheduleId'));
        } else {

            $scheduleMaster = new ScheduleMaster();
        }

        $form = $this->createForm(new ScheduleMasterType(true, $request), $scheduleMaster);
        $form->handleRequest($request);
        $event = $this->getDoctrine()->getRepository('EventBundle:Event')
            ->find($scheduleMaster->getEventId());
        $scheduleMaster->setEvent($event);
            if ($form->isValid()) {
                $scheduleEvent = $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')
                    ->findOneByEvent($scheduleMaster->getEventId());
                if(!$scheduleEvent){
                    $hasSchedule = 0;
                    $scheduleMaster->setEvent($event);
                    if($scheduleMaster->getDaysOfWeek())
                    {
                        $days = implode(",", $scheduleMaster->getDaysOfWeek());
                        $scheduleMaster->setDaysOfWeek($days);
                    }
                    if($scheduleMaster->getDaysOfMonth())
                    {
                        $days = implode(",", $scheduleMaster->getDaysOfMonth());
                        $scheduleMaster->setDaysOfMonth($days);
                    }
                    $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')->create($scheduleMaster);
                }else
                {
                    if($scheduleMaster->getDaysOfWeek())
                    {
                        $days = implode(",", $scheduleMaster->getDaysOfWeek());
                        $oldDays = $scheduleEvent->getDaysOfWeek();
                        $updateDaysOfWeek = $days.','.$oldDays;
                        $scheduleEvent->setDaysOfWeek($updateDaysOfWeek);
                        $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')->create($scheduleEvent);
                    }
                    if($scheduleMaster->getDaysOfMonth())
                    {
                        $days = implode(",", $scheduleMaster->getDaysOfMonth());
                        $oldDays = $scheduleEvent->getDaysOfMonth();
                        $updateDaysOfMonth = $days.','.$oldDays;
                        $scheduleEvent->setDaysOfMonth($updateDaysOfMonth);
                        $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')->create($scheduleEvent);
                    }
                    $hasSchedule = 1;
                    if($scheduleEvent->getStartDate() > $scheduleMaster->getStartDate()){
                        $scheduleEvent->setStartDate($scheduleMaster->getStartDate());
                        $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')->create($scheduleEvent);
                    }
                    if($scheduleEvent->getEndDate() < $scheduleMaster->getEndDate()){
                        $scheduleEvent->setEndDate($scheduleMaster->getEndDate());
                        $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')->create($scheduleEvent);
                    }

                }

              /*  $scheduleMaster->setStartDate($scheduleMaster->getStartDate());
                $scheduleMaster->setEndDate($scheduleMaster->getEndDate());
                $scheduleMaster->setStartTime($scheduleMaster->getStartTime());
                $scheduleMaster->setEndTime($scheduleMaster->getEndTime());*/
                $this->setScheduleDetails($request,$event,$scheduleMaster);
                $master= $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')
                    ->findOneByEvent($event);
                $scheduleInfo = $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')
                    ->find($master->getId());
                $scheduleDetailCount = $this->getDoctrine()->getRepository('EventBundle:ScheduleDetail')
                    ->findByEvent($event);
                $dates = count($scheduleDetailCount);
                $list = $this->scheduleInfoConvertToArray($scheduleInfo,$dates,$hasSchedule);

                $response = new JsonResponse($list);
                return $response;
            }
    }
    public function scheduleInfoConvertToArray($scheduleInfo,$dates,$hasSchedule){

        $array = array();

        $array['scheduleId'] = $scheduleInfo->getId();
        $array['hasSchedule'] = $hasSchedule;
        $array['frequency'] = $scheduleInfo->getFrequency();
        //$days = date_diff($scheduleInfo->getStartDate(),$scheduleInfo->getEndDate());
        //$count = $days->format("%a Dates") ;
        $array['day'] = $dates;
        $array['startDate'] = date_format($scheduleInfo->getStartDate(), ' jS F Y');
        $array['startTime'] = date_format($scheduleInfo->getStartTime(), 'g:iA');
        $array['endDate']   = date_format($scheduleInfo->getEndDate(), ' jS F Y');
        $array['endTime']   = date_format($scheduleInfo->getEndTime(), 'g:iA');
        $array['eventId']   = $scheduleInfo->getEvent()->getId();

        return $array;
    }
    private function createDateRangeArray($strDateFrom,$strDateTo)
    {
        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }
    private function setScheduleDetails($request,$event,$scheduleMaster)
    {
        $masterEvent = $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')
            ->findOneByEvent($event);
        /*$startDate = $masterEvent->getStartDate()->format('Y-m-d');
        $endDate = $masterEvent->getEndDate()->format('Y-m-d');*/
        $startDateDetails = $request->request->all()['eventbundle_schedule_master']['startDate'];
        $endDateDetails = $request->request->all()['eventbundle_schedule_master']['endDate'];
        //$dates = $this->createDateRangeArray($startDate,$endDate);
        $datesDetails = $this->createDateRangeArray($startDateDetails,$endDateDetails);
        if($scheduleMaster->getFrequency() == 'Daily')
        {
            foreach($datesDetails as $datesDetail) {
                $this->createScheduleDetailsAction($request,$event,$scheduleMaster,$datesDetail);
            }
        }elseif($scheduleMaster->getFrequency() == 'Weekly'){
            $daysSet = implode(",", $request->request->all()['eventbundle_schedule_master']['daysOfWeek']);
            $days = explode(',',$daysSet);

            foreach($datesDetails as $datesDetail) {
                $day2 = date_format(new \DateTime($datesDetail), 'l');
                if(in_array($day2,$days))
                {
                    $this->createScheduleDetailsAction($request,$event,$scheduleMaster,$datesDetail);
                }
            }
        }elseif($scheduleMaster->getFrequency() == 'Monthly') {
            $daysSet = implode(",", $request->request->all()['eventbundle_schedule_master']['daysOfMonth']);
            $days = explode(',',$daysSet);
            foreach($datesDetails as $datesDetail) {
                $day3 = date_format(new \DateTime($datesDetail), 'jS');
                if(in_array($day3,$days))
                {
                    $this->createScheduleDetailsAction($request,$event,$scheduleMaster,$datesDetail);
                }
            }
        }
    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function detailsAction(ScheduleMaster $scheduleMaster)
    {

        $em = $this->getDoctrine()->getManager();
        $masterDetails = $em->getRepository('EventBundle:ScheduleDetail')->findByMaster($scheduleMaster->getId());

        $data = array();
        foreach($masterDetails as $key=>$master){
            $data[$key]['id'] = $master->getId();
            $data[$key]['date'] = date_format($master->getScheduleDate(), 'l jS F Y');
            $data[$key]['startTime'] = date_format($master->getStartTime(), 'g:iA');
            $data[$key]['endTime'] = date_format($master->getEndTime(), 'g:iA');
        }

        $response = new JsonResponse($data);
        return $response;

    }

    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function deleteAction(ScheduleMaster $scheduleMaster)
    {

        $em = $this->getDoctrine()->getManager();
        $master = $em->getRepository('EventBundle:ScheduleMaster')->find($scheduleMaster->getId());

        if (!$master) {
            throw $this->createNotFoundException('No guest found for id '.$master->getId());
        }
        $em->getRepository('EventBundle:ScheduleMaster')->delete($master);

        $message = array('Schedule  Successfully Deleted');
        return new JsonResponse($message);
    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function deleteDetailScheduleAction(ScheduleDetail $scheduleDetail)
    {

        $em = $this->getDoctrine()->getManager();
        $detail = $em->getRepository('EventBundle:ScheduleDetail')->find($scheduleDetail->getId());

        if (!$detail) {
            throw $this->createNotFoundException('No guest found for id '.$detail->getId());

        }

        $em = $this->getDoctrine()->getManager();
        $scheduleMaster = $em->getRepository('EventBundle:ScheduleMaster')->find($scheduleDetail->getMaster());
       if($scheduleDetail->getScheduleDate() == $scheduleMaster->getEndDate())
        {
            $em->getRepository('EventBundle:ScheduleDetail')->delete($detail);

            $countDetail = $em->getRepository('EventBundle:ScheduleDetail')->findByMaster($scheduleDetail->getMaster());
            $lastScheduleDetail = end($countDetail);
            $scheduleMaster->setEndDate($lastScheduleDetail->getScheduleDate());
            $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')->update($scheduleMaster);

        }else{
           $em->getRepository('EventBundle:ScheduleDetail')->delete($detail);

           $countDetail = $em->getRepository('EventBundle:ScheduleDetail')->findByMaster($scheduleDetail->getMaster());
           $lastScheduleDetail = end($countDetail);
       }

        $calculateDate = count($countDetail);
        if($calculateDate == 0)
        {
            $em->getRepository('EventBundle:ScheduleMaster')->delete($scheduleMaster);
        }
        $idList = array(
            'dates'=>$calculateDate,
            'lastScheduleDetail'=>date_format($lastScheduleDetail->getScheduleDate(), ' jS F Y')
        );
        $response = new JsonResponse($idList);
        return $response;
    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    private function createScheduleDetailsAction($request,$event,$scheduleMaster,$date)
    {
        $matchDate = $this->getDoctrine()->getRepository('EventBundle:ScheduleDetail')
            ->findOneBy(array('event' => $event, 'scheduleDate' => new \DateTime($date)));
        if(!$matchDate){
            $scheduleDetails = new ScheduleDetail();
            $scheduleDetails->setStartTime($scheduleMaster->getStartTime());
            $scheduleDetails->setEndTime($scheduleMaster->getEndTime());
            $scheduleDetails->setScheduleDate(new \DateTime($date));
            $scheduleDetails->setEvent($event);
            $master= $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')
                ->findOneByEvent($event);
            $schedule = $this->getDoctrine()->getRepository('EventBundle:ScheduleMaster')
                ->find($master->getId());
            $scheduleDetails->setMaster($schedule);
            $this->getDoctrine()->getRepository('EventBundle:ScheduleDetail')->create($scheduleDetails);
        }

    }


}
