<?php

namespace Funstaff\Bundle\RedirectBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Funstaff\Bundle\RedirectBundle\Csv\CsvImporter;
use Funstaff\Bundle\RedirectBundle\Csv\CsvExporter;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;
use Funstaff\Bundle\RedirectBundle\FunstaffRedirectEvents;
use Funstaff\Bundle\RedirectBundle\Event\RedirectEvent;

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
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var CsvImporter
     */
    private $csvImporter;

    /**
     * @var CsvExporter
     */
    private $csvExporter;

    /**
     * @var classname
     */
    private $class;

    /**
     * @var boolean
     */
    private $statEnabled;

    /**
     * @var Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * Constructor
     *
     * @param ObjectManager             $om
     * @param EventDispatcherInterface  $dispatcher
     * @param CsvImporter               $csvImporter
     * @param CsvExporter               $csvExporter
     * @param string                    $class
     * @param boolean                   $statEnabled
     */
    public function __construct(ObjectManager $om, EventDispatcherInterface $dispatcher, CsvImporter $csvImporter, CsvExporter $csvExporter, $class, $statEnabled = false)
    {
        $this->om = $om;
        $this->dispatcher = $dispatcher;
        $this->csvImporter = $csvImporter;
        $this->csvExporter = $csvExporter;
        $this->class = $class;
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
        $baseUrl = $request->getBaseUrl();
        $requestUri = $request->getRequestUri();

        $source = mb_eregi_replace($baseUrl, '', $requestUri);
        $source = ltrim($source, '/');

        $redirect = $this->getRepository()
                        ->getDestinationFromSource($source);

        if (!$redirect) {
            return;
        }

        if ($this->isStatEnabled()) {
            $this->updateStat($redirect);
        }

        $destination = sprintf(
            '%s/%s',
            $baseUrl,
            $redirect->getDestination()
        );

        $event = new RedirectEvent($redirect, $request);
        $this->dispatcher->dispatch(
            FunstaffRedirectEvents::BEFORE_REDIRECT,
            $event
        );

        return new RedirectResponse($destination, $redirect->getStatusCode());
    }

    /**
     * Save
     *
     * @param Funstaff\Bundle\RedirectBundle\Entity\Redirect $redirect
     */
    public function save(Redirect $redirect)
    {
        $this->om->persist($redirect);
        $this->om->flush();
    }

    /**
     * Delete
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $record = $this->repository->find($id);
        if (!$record) {
            throw new NotFoundHttpException('Unable to find Redirect entity.');
        }

        $this->om->remove($record);
        $this->om->flush();
    }

    /**
     * Export
     *
     * @param string Full path of file
     */
    public function export($path)
    {
        $collection = $this->getRepository()
                        ->getAllOrderBySource()
                        ->getQuery()
                        ->getResult();

        return $this->csvExporter->export($path, $collection);
    }

    /**
     * Import
     *
     * @param string Full path of file
     */
    public function import($path)
    {
        return $this->csvImporter->import($path);
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
        $this->save($redirect);
    }

    /**
     * Get Class
     *
     * @return Fullpath of class
     */
    public function getClass()
    {
        return $this->class;
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