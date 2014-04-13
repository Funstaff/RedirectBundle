<?php

namespace Funstaff\Bundle\RedirectBundle\Csv;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * AbstractCsv.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
abstract class AbstractCsv
{
    protected $om;

    protected $class;

    protected $repository;

    /**
     * Constructor
     *
     * @param ObjectManager     $om
     * @param string            $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->om = $om;
        $this->class = $class;
        $this->repository = $om->getRepository($class);
    }

    /**
     * Get class
     *
     * @return string   $class
     */
    protected function getClass()
    {
        return $this->class;
    }

    /**
     * Get Repository
     *
     * @return Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->repository;
    }

    /**
     * Get Metadata
     *
     * @return Doctrine\ORM\ClassMetadata
     */
    protected function getMetadata()
    {
        return $this->om->getClassMetadata($this->getClass());
    }
}