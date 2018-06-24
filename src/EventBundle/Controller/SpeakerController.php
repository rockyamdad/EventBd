<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Event;
use EventBundle\Entity\Speaker;
use EventBundle\Form\SpeakerType;
use JMS\SecurityExtraBundle\Annotation as JMS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class SpeakerController extends Controller
{

    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request)
    {
        $speaker = new Speaker();
        $allData = $request->request->all();

        $form = $this->createForm(new SpeakerType(), $speaker);
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var UploadedFile $file */
                $file = $request->files->all()['eventbundle_speaker']['file'];

                $speakerEvent = $this->getDoctrine()->getRepository('EventBundle:Event')->find($allData['eventbundle_speaker']['event']);

                $speaker->setEvent($speakerEvent);
                if($file)
                {
                    $uploaded = $file->move($speaker->getUploadRootDir(), $file->getClientOriginalName());
                    $speaker->setPath($uploaded->getFilename());
                }

                $this->getDoctrine()->getRepository('EventBundle:Speaker')->create($speaker);
                $speakerEvent->setHasSpeaker(1);
                $this->getDoctrine()->getRepository('EventBundle:Event')->update($speakerEvent);
                $speakerInfo = $this->getDoctrine()->getRepository('EventBundle:Speaker')
                    ->find($speaker->getId());

                $list = $this->speakerInfoConvertToArray($speakerInfo);
            }
        }
        return new JsonResponse($list);

    }
    public function speakerInfoConvertToArray($speakerInfo){

        $array = array();

        $array['id'] = $speakerInfo->getId();
        $array['firstName'] = $speakerInfo->getFirstName();
        $array['lastName'] = $speakerInfo->getLastName();
        $array['description'] = $speakerInfo->getDescription();
        $array['photo'] = $speakerInfo->getPath();
        return $array;
    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function deleteAction(Speaker $speaker)
    {

        $em = $this->getDoctrine()->getManager();
        $speaker = $em->getRepository('EventBundle:Speaker')->find($speaker->getId());

        if (!$speaker) {
            throw $this->createNotFoundException('No guest found for id '.$speaker->getId());
        }
        $em->getRepository('EventBundle:Speaker')->delete($speaker);
        $message = array('Speaker Successfully Deleted');
        return new JsonResponse($message);
    }
}
