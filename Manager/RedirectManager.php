<?php

namespace Funstaff\Bundle\RedirectBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;

/**
 * RedirectManager.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectManager
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var boolean
     */
    private $statEnabled;

    /**
     * @var Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @param ObjectManager $om
     * @param string        $class
     */
    public function __construct(ObjectManager $om, $class, $statEnabled = false)
    {
        $this->om = $om;
        $this->statEnabled = (bool) $statEnabled;
        $this->repository = $om->getRepository($class);
    }

    /**
     * Get Response from Request
     *
     * @param Request   $request
     *
     * @return mixed    null or RedirectResponse
     */
    public function getResponseFromRequest(Request $request)
    {
        $source = rtrim($request->getRequestUri());

        $redirect = $this->getRepository()
                        ->getDestinationFromSource($source);

        if ($redirect && $this->isStatEnabled()) {
            $this->updateStat($redirect);
        }

        return ($redirect) ?
                new RedirectResponse(
                    $redirect->getDestination(),
                    $redirect->getStatusCode()
                ) : null;
    }

    /**
     * update Stat
     *
     * @param Redirect  $redirect
     */
    private function updateStat(Redirect $redirect)
    {
        $redirect
            ->setLastAccessed(new \DateTime())
            ->increaseStatCount();
        $this->om->persist($redirect);
        $this->om->flush();
    }

    /**
     * Get Repository
     *
     * @return ObjectRepository
     */
    private function getRepository()
    {
        return $this->repository;
    }

    /**
     * is Stat Enabled
     *
     * @return boolean
     */
    private function isStatEnabled()
    {
        return $this->statEnabled;
    }
}