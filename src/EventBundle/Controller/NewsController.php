<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Event;
use EventBundle\Entity\News;
use EventBundle\Form\NewsType;
use JMS\SecurityExtraBundle\Annotation as JMS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class NewsController extends Controller
{

    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request)
    {
        $news = new News();
        $allData = $request->request->all();
        $form = $this->createForm(new NewsType(), $news);
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $newsEvent = $this->getDoctrine()->getRepository('EventBundle:Event')->find($allData['eventbundle_news']['event']);
                $news->setEvent($newsEvent);
                $this->getDoctrine()->getRepository('EventBundle:News')->create($news);
                $newsEvent->setHasNews(1);
                $this->getDoctrine()->getRepository('EventBundle:Event')->update($newsEvent);

                $newsInfo = $this->getDoctrine()->getRepository('EventBundle:News')
                    ->find($news->getId());

                $list = $this->newsInfoConvertToArray($newsInfo);
            }
        }

        return new JsonResponse($list);

    }
    public function newsInfoConvertToArray($newsInfo){

        $array = array();
        $array['id'] = $newsInfo->getId();
        $array['title'] = $newsInfo->getTitle();
        $array['type'] = $newsInfo->getType();
        $array['details'] = $newsInfo->getDetails();

        return $array;
    }
    /**
     * @JMS\Secure(roles="ROLE_USER")
     */
    public function deleteAction(News $news)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('EventBundle:News')->find($news->getId());

        if (!$news) {
            throw $this->createNotFoundException('No guest found for id '.$news->getId());
        }
        $em->getRepository('EventBundle:News')->delete($news);

        $message = array('News  Successfully Deleted');
        return new JsonResponse($message);
    }
}
