<?php
namespace UserBundle\Controller;

use JMS\SecurityExtraBundle\Annotation as JMS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;

class UserController extends Controller
{
    public function indexAction()
    {
         return $this->render('UserBundle:Users:dashboard.html.twig');
    }
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function userListAction()
    {
        $userDatatable = $this->get("sg_datatables.user");

        return $this->render('UserBundle:Users:list.html.twig',array(
            'datatable' => $userDatatable
        ));
    }
    public function indexResultsAction()
    {
        /**
         * @var \Sg\DatatablesBundle\Datatable\Data\DatatableData $datatable
         */
        $datatable = $this->get("sg_datatables.datatable")->getDatatable($this->get("sg_datatables.user"));

        return $datatable->getResponse();
    }

    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addAction(Request  $request)
    {
        $users = new User();
        $form = $this->createForm(new UserType(true,false),$users);
        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $data = $this->getRequest()->request->all();
                $users->setUsername($data['userbundle_user']['email']);
                $users->setRoles(array($data['userbundle_user']['single_role']));
                $users->setEnabled(1);
                $users->setStatus('Activate');
                $this->getDoctrine()->getRepository('UserBundle:User')->create($users);
                $this->get('session')->getFlashBag()->add(
                    'message',
                    'User Successfully created'
                );
               return $this->redirect($this->generateUrl('users_list'));
            }
        }
        return $this->render('UserBundle:Users:add.html.twig',array(
            'form' => $form->createView()
        ));
    }
    public function deactiveListAction()
    {
        return $this->render('UserBundle:Users:deactiveList.html.twig');
    }
    /**
     * @JMS\Secure(roles="ROLE_ADMIN,ROLE_USER")
     */
    public function profileEditAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new UserType(false,true),$user);
        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
               $this->get('fos_user.user_manager')->updateUser($user);
                $this->get('session')->getFlashBag()->add(
                    'message',
                    'Profile Successfully Updated'
                );
                if(in_array('ROLE_ADMIN',$user->getRoles()))
                {
                    return $this->redirect($this->generateUrl('user_profile',array('id'=>$user->getId())));
                }elseif(in_array('ROLE_USER',$user->getRoles()));
                {
                    return $this->redirect($this->generateUrl('fos_user_profile_show'));
                }

            }
        }
        if(in_array('ROLE_ADMIN',$user->getRoles()))
        {
            return $this->render('UserBundle:Users:profile.html.twig',array(
                'form' => $form->createView()
            ));
        }elseif(in_array('ROLE_USER',$user->getRoles()));
        {
            return $this->render('UserBundle:Users:userProfileEdit.html.twig',array(
                'form' => $form->createView()
            ));
        }
    }
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function changeStatusAction($current, User $user)
    {
        if($user->getStatus() == $current) {
            $user->setStatus($current == 'Activate' ? 'Deactivate': 'Activate');
            $user->setEnabled($current == 'activate' ? 0:1);

            $this->get('fos_user.user_manager')->updateUser($user);
        }
        return new JsonResponse(array(
            'id' => $user->getId(),
            'status' => $user->getStatus()
        ));
    }
    /**
     * @JMS\Secure(roles="ROLE_ADMIN")
     */
    public function editAction(Request $request,User $user)
    {
        $form = $this->createForm(new UserType(true,true),$user);
        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $this->get('fos_user.user_manager')->updateUser($user);
                $this->get('session')->getFlashBag()->add(
                    'message',
                    'User Profile Successfully Updated'
                );
              return $this->redirect($this->generateUrl('users_list'));
            }
        }
            return $this->render('UserBundle:Users:edit.html.twig',array(
                'form' => $form->createView()
            ));
    }
    public function duplicateEmailCheckAction(Request $request)
    {
        $userEmail = $request->request->get("userbundle_user[email]", NULL, TRUE);
        $duplicateEmail =  $this->getDoctrine()->getRepository('UserBundle:User')->findByEmail($userEmail);
        if ($duplicateEmail && $duplicateEmail[0]->getId() != $request->request->get("user_id")) {
            $response = 'false';
        } else {
            $response = 'true';
        }
        return new Response($response);
    }

}