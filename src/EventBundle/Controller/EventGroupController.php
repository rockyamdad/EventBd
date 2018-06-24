<?php

namespace EventBundle\Controller;

use JMS\SecurityExtraBundle\Annotation as JMS;
use EventBundle\Entity\EventGroup;
use EventBundle\Form\EventGroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class EventGroupController extends Controller
{
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function indexAction()
    {
        $groupDatatable = $this->get("sg_datatables.group");

        return $this->render('EventBundle:Group:list.html.twig',array(
            'datatable' => $groupDatatable
        ));
    }

    public function indexResultsAction()
    {
        /**
         * @var \Sg\DatatablesBundle\Datatable\Data\DatatableData $datatable
         */
        $datatable = $this->get("sg_datatables.datatable")->getDatatable($this->get("sg_datatables.group"));

        return $datatable->getResponse();
    }
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $eventGroups = new EventGroup();
        $form = $this->createForm(new EventGroupType(),$eventGroups);
        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $eventGroups->setUploadedBy($this->getUser());
                $eventGroups->setUploadedDate(new \DateTime());

                $this->getDoctrine()->getRepository('EventBundle:EventGroup')->create($eventGroups);
                $this->get('session')->getFlashBag()->add(
                    'message',
                    'Event Group Successfully Created'
                );
                return $this->redirect($this->generateUrl('event_group_list'));
            }
        }
        return $this->render('EventBundle:Group:add.html.twig',array(
            'form' => $form->createView()
        ));
    }
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function editAction(Request $request,EventGroup $eventGroup)
    {
        $form = $this->createForm(new EventGroupType(),$eventGroup);
        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getRepository('EventBundle:EventGroup')->update($eventGroup);
                $this->get('session')->getFlashBag()->add(
                    'message',
                    'Event Group Successfully Updated'
                );
                return $this->redirect($this->generateUrl('event_group_list'));
            }
        }
        return $this->render('EventBundle:Group:edit.html.twig',array(
            'form' => $form->createView()
        ));
    }
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(EventGroup $eventGroup)
    {
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository('EventBundle:EventGroup')->find($eventGroup->getId());

        if (!$group) {
            throw $this->createNotFoundException('No guest found for id '.$eventGroup->getId());
        }
        $em->getRepository('EventBundle:EventGroup')->delete($group);

        $this->get('session')->getFlashBag()->add(
            'message',
            'Event Group Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('event_group_list'));
    }

    public function allGroupAction()
    {

        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('EventBundle:EventGroup')->findAll();

        return $this->render(
            'FrontendBundle:Default:headerCategory.html.twig',
            array('groups'=>$groups)
        );


    }
}
