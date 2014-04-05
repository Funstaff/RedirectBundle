<?php

namespace Funstaff\Bundle\RedirectBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Funstaff\Bundle\RedirectBundle\Manager\RedirectManager;

/**
 * RedirectExceptionListener.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectExceptionListener
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
        if (!$exception instanceOf NotFoundHttpException) {
            return;
        }

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