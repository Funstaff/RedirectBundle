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

        $exception = $event->getException();
        $request = $event->getRequest();
        if (404 != $exception->getStatusCode() || 'GET' != $request->getMethod()) {
            return;
        }

        $response = $this->manager->getResponseFromRequest($request);
        if (null != $response) {
            $event->setResponse($response);
        }
    }
}