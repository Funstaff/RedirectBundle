<?php

namespace Funstaff\Bundle\RedirectBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Funstaff\Bundle\RedirectBundle\Manager\RedirectManager;

/**
 * RedirectRequestListener.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectRequestListener
{
    /**
     * @var RedirectManager
     */
    private $manager;

    /**
     * @param RedirectManager $$manager
     */
    public function __construct(RedirectManager $manager)
    {
        $this->manager = $manager;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $response = $this->manager->getResponseFromRequest($request);
        if (null != $response) {
            $event->setResponse($response);
        }
    }
}