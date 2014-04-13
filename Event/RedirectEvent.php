<?php

namespace Funstaff\Bundle\RedirectBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;
use Symfony\Component\HttpFoundation\Request;

/**
 * RedirectEvent.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectEvent extends Event
{
    private $redirect;

    private $request;

    /**
     * Constructor
     *
     * @param Redirect $redirect
     * @param Request $request
     */
    public function __construct(Redirect $redirect, Request $request)
    {
        $this->redirect = $redirect;
        $this->request = $request;
    }

    /**
     * Get Entity
     *
     * @return Funstaff\Bundle\RedirectBundle\Entity\Redirect
     */
    public function getEntity()
    {
        return $this->redirect;
    }

    /**
     * Get Request
     *
     * @return Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}