<?php

namespace Funstaff\Bundle\RedirectBundle\Tests\Csv;

abstract class AbstractCsvTest extends \PHPUnit_Framework_TestCase
{
    protected function getClass()
    {
        return '\Funstaff\Bundle\RedirectBundle\Entity\Redirect';
    }

    protected function getEntityManager()
    {
        return $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
    }
}