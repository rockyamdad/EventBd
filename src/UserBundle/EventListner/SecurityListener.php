<?php
namespace UserBundle\EventListner;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher ;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityListener
{
    protected $router;
    protected $security;
    protected $dispatcher;

    public function __construct(Router $router, SecurityContext $security, EventDispatcher $dispatcher)
    {
        $this->router = $router;
        $this->security = $security;
        $this->dispatcher = $dispatcher;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $currentUrl = $_SERVER['HTTP_REFERER'];
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse($currentUrl);
        } /*elseif ($this->security->isGranted('ROLE_VENDOR')) {
            $response = new RedirectResponse($this->router->generate('vendor_profile'));
        }*/
        elseif ($this->security->isGranted('ROLE_USER')) {
            $response = new RedirectResponse($currentUrl);
        }
        $event->setResponse($response);
    }
}