<?php

namespace Funstaff\Bundle\RedirectBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;
use Funstaff\Bundle\RedirectBundle\Serializer\Serializer;

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
     * @var Serializer
     */
    private $serializer;

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
    public function __construct(ObjectManager $om, Serializer $serializer, $class, $statEnabled = false)
    {
        $this->om = $om;
        $this->serializer = $serializer;
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

        return new RedirectResponse($destination, $redirect->getStatusCode());
    }

    /**
     * Export
     *
     * @param string Full path of file
     */
    public function export($path)
    {
        $records = $this->getRepository()->findAll();
        $data = $this->serializer->serialize($records, 'text');
        file_put_contents($path, $data);
    }

    /**
     * Import
     *
     * @param string Full path of file
     */
    public function import($path)
    {
        $content = file_get_contents($path);
        $lines = explode("\n", $content);
        if (count($lines) > 0) {
            $keys = explode("\t", $lines[0]);
            unset($lines[0]);
        }

        foreach ($lines as $line) {
            $datas = explode("\t", $line);
            $values = array();
            foreach ($datas as $id => $data) {
                $values[$keys[$id]] = $data;
            }
            $redirect = $this->serializer->deserialize(
                        $values,
                        'Funstaff\Bundle\RedirectBundle\Entity\Redirect',
                        'text'
                    );
            $record = $this->getRepository()
                        ->findOneBy(array('source' => $redirect->getSource()));
            if ($record) {
                $record
                    ->setDestination($redirect->getDestination())
                    ->setStatusCode($redirect->getStatusCode());
            } else {
                $record = new Redirect();
                $record
                    ->setSource($redirect->getSource())
                    ->setDestination($redirect->getDestination())
                    ->setStatusCode($redirect->getStatusCode());
                $this->om->persist($record);
            }
        }
        if ($this->om->getUnitOfWork()->size()) {
            $this->om->flush();
        }
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