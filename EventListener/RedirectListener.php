<?php

namespace Funstaff\Bundle\RedirectBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Funstaff\Bundle\RedirectBundle\Manager\RedirectManager;

/**
 * RedirectListener.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectListener
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

    public function onCoreException(GetResponseForExceptionEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        if ($request->getMethod() != 'GET') {
            return;
        }

        $response = $this->manager->getResponseFromRequest($request);
        if (null != $response) {
            $event->setResponse($response);
        }
    }
}