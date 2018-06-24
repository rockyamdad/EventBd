<?php
namespace VendorBundle\Controller;

use JMS\SecurityExtraBundle\Annotation as JMS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use VendorBundle\Entity\VendorProfile;
use VendorBundle\Form\VendorProfileType;

class VendorProfileController extends Controller
{
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function indexAction()
    {
        $vendorDatatable = $this->get("sg_datatables.vendor");

        return $this->render('VendorBundle:Vendors:list.html.twig',array(
            'datatable' => $vendorDatatable
        ));
    }

    public function indexResultsAction()
    {
        /**
         * @var \Sg\DatatablesBundle\Datatable\Data\DatatableData $datatable
         */
        $datatable = $this->get("sg_datatables.datatable")->getDatatable($this->get("sg_datatables.vendor"));

        return $datatable->getResponse();
    }

    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function addVendorAction(Request $request)
    {
        $vendors = new VendorProfile();
        $form = $this->createForm(new VendorProfileType(true),$vendors);
        $form->handleRequest($request);
        if($request->getMethod()=='POST')
        {
            if ($form->isValid()) {
                $user = $vendors->getUser();
                $user->setUsername($user->getEmail());
                $vendors->upload();
                $user->setRoles(array('ROLE_VENDOR'));
                $user->setEnabled(1);
                $user->setStatus('Activate');
                $this->getDoctrine()->getRepository('UserBundle:User')->create($user);
                $this->getDoctrine()->getRepository('VendorBundle:VendorProfile')->create($vendors);
                $this->get('session')->getFlashBag()->add(
                    'message',
                    'Vendor Successfully created'
                );

                return $this->redirect($this->generateUrl('vendor_list'));
            }
        }
        return $this->render("VendorBundle:Vendors:add.html.twig",array(
            'form' => $form->createView()
        ));
    }
    /**
     * @JMS\Secure(roles="ROLE_VENDOR")
     */
    public function profileAction()
    {
        $id = $this->getUser();
        $vendor = $this->getDoctrine()->getRepository('VendorBundle:VendorProfile')->findOneBy(array('user' =>$id));

        return $this->render('VendorBundle:Vendors:profile.html.twig',array(
            'vendor' => $vendor
        ));
    }
    /**
     * @JMS\Secure(roles="ROLE_VENDOR")
     */
    public function profileEditAction(Request $request)
    {
        $user = $this->getUser();
        $vendorProfile = $this->getDoctrine()->getRepository('VendorBundle:VendorProfile')->findOneBy(array('user' => $user));
        $form = $this->createForm(new VendorProfileType(false),$vendorProfile);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getRepository('VendorBundle:VendorProfile')->update($vendorProfile);
                $this->get('session')->getFlashBag()->add(
                    'message',
                    'Profile Successfully Updated'
                );
                return $this->redirect($this->generateUrl('vendor_profile'));
            }
        }
        return $this->render('VendorBundle:Vendors:profileEdit.html.twig',array(
            'form' => $form->createView()
        ));
    }
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function editAction(Request $request,User $user)
    {

        $vendorsProfile = $this->getDoctrine()->getRepository('VendorBundle:VendorProfile')->findOneBy(array('user' => $user));
        $form = $this->createForm(new VendorProfileType(false),$vendorsProfile);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getRepository('VendorBundle:VendorProfile')->update($vendorsProfile);
                $this->get('session')->getFlashBag()->add(
                    'message',
                    'Profile Successfully Updated'
                );
                return $this->redirect($this->generateUrl('vendor_list'));
            }
        }
        return $this->render('VendorBundle:Vendors:edit.html.twig',array(
            'form' => $form->createView()
        ));
    }
}