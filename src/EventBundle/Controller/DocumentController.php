<?php

namespace EventBundle\Controller;

use Doctrine\ORM\Repository;
use EventBundle\Entity\Document;
use EventBundle\Form\DocumentType;

use JMS\SecurityExtraBundle\Annotation as JMS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Document controller.
 *
 */
class DocumentController extends Controller
{
    public function indexAction()
    {
        $documentList = $this->getDoctrine()->getRepository('EventBundle:Document')->getAll();
        return $this->render('EventBundle:Document:home.html.twig', array(
            'documentList' =>$documentList
        ));
    }


    public function uploadFileAction(Request $request)
    {
        $document = new Document();

        $form = $this->createForm(new DocumentType(), $document);
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $document->setUploadedBy($this->getUser());
                $document->setUploadedDate(new \DateTime());

                $this->getDoctrine()->getRepository('EventBundle:Document')->create($document);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'File Successfully Upload'
                );

                return $this->redirect($this->generateUrl('document_list'));
            }
        }

        return $this->render('EventBundle:Document:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function updateAction(Request $request , Document $document)
    {
        $form = $this->createForm(new DocumentType(), $document);
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);
            if ($form->isValid()) {

                $document->setUploadedBy($this->getUser());
                $document->setUploadedDate(new \DateTime());

                $this->getDoctrine()->getRepository('EventBundle:Document')->create($document);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'File Successfully Upload'
                );

                return $this->redirect($this->generateUrl('document_list'));
            }
        }

        return $this->render('EventBundle:Document:form.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function deleteAction(Document $document)
    {
        $this->getDoctrine()->getRepository('EventBundle:Document')->delete($document);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Document Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('document_list'));
    }


}
