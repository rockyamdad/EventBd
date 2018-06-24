<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Event;
use EventBundle\Entity\Sponsor;
use EventBundle\Form\SponsorType;
use JMS\SecurityExtraBundle\Annotation as JMS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class SponsorController extends Controller
{

    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request)
    {
        $sponsor = new Sponsor();
        $allData = $request->request->all();
        $form = $this->createForm(new SponsorType(), $sponsor);
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var UploadedFile $file */
                $file = $request->files->all()['eventbundle_sponsor']['file'];

                $sponsorEvent = $this->getDoctrine()->getRepository('EventBundle:Event')->find($allData['eventbundle_sponsor']['event']);

                $sponsor->setEvent($sponsorEvent);
                $uploaded = $file->move($sponsor->getUploadRootDir(), $file->getClientOriginalName());
                if ($uploaded) {
                    $sponsor->setPath($uploaded->getFilename());
                }
                $this->getDoctrine()->getRepository('EventBundle:Sponsor')->create($sponsor);
                $sponsorEvent->setHasSponsor(1);
                $this->getDoctrine()->getRepository('EventBundle:Event')->update($sponsorEvent);

                $sponsorInfo = $this->getDoctrine()->getRepository('EventBundle:Sponsor')
                    ->find($sponsor->getId());

                $list = $this->sponsorInfoConvertToArray($sponsorInfo);
            }
        }
        return new JsonResponse($list);

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
    public function deleteAction(Sponsor $sponsor)
    {

        $em = $this->getDoctrine()->getManager();
        $sponsor = $em->getRepository('EventBundle:Sponsor')->find($sponsor->getId());

        if (!$sponsor) {
            throw $this->createNotFoundException('No guest found for id '.$sponsor->getId());
        }
        $em->getRepository('EventBundle:Sponsor')->delete($sponsor);

        $message = array('Sponsor  Successfully Deleted');
        return new JsonResponse($message);
    }
}
