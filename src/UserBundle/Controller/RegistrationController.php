<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;
use Symfony\Component\Security\Core\SecurityContextInterface;

class RegistrationController extends Controller
{
    public function saveUserAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(false,false),$user);

        if($request->getMethod() == 'POST') {
            $form->submit($request);

            if ($form->isValid()) {

                $data = $request->request->all();
                $user->setUsername($data['userbundle_user']['email']);
                $user->setEnabled(1);
                $user->setStatus('Activate');
                $this->getDoctrine()->getRepository('UserBundle:User')->create($user);

                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);

                $this->get('session')->getFlashBag()->add(
                    'message',
                    'Registration Successfully Completed'
                );

               /* $this->get('session')->getFlashBag()->add(
                    'registered',
                    true
                );*/

                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->render('UserBundle:Registration:register.html.twig',array(
            'entity' => $user,
            'form' => $form->createView()
        ));
    }

}
