<?php

namespace Funstaff\Bundle\RedirectBundle\Tests\Event;

use Symfony\Component\HttpFoundation\Request;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;
use Funstaff\Bundle\RedirectBundle\Event\RedirectEvent;

/**
 * RedirectEventTest.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectEventTest extends \PHPUnit_Framework_TestCase
{
    public function testRedirectEvent()
    {
        $event = new RedirectEvent(
            new Redirect(),
            new Request()
        );

        $this->assertInstanceOf('Funstaff\Bundle\RedirectBundle\Entity\Redirect', $event->getRedirect());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $event->getRequest());
    }
}