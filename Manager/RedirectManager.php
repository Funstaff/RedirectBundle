<?php

namespace Funstaff\Bundle\RedirectBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

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
     */
    public function export($path, $statistic = false)
    {
        $directory = dirname($path);
        if (!is_writable($directory)) {
            throw new \Exception(sprintf(
                "The path %s isn't writable",
                $directory
            ));
        }
        $options = array('json_encode_options' => JSON_PRETTY_PRINT);
        $records = $this->getRepository()->findAll();
        $serializer = $this->initializeSerializer($statistic);
        $data = $serializer->serialize($records, 'json', $options);
        file_put_contents($path, $data);
    }

    /**
     * initializeSerializer
     *
     * @return Symfony\Component\Serializer\Serializer
     */
    private function initializeSerializer($statistic)
    {
        $ignoredFields = array('id', 'createdAt', 'updatedAt');
        $encoders = array(new JsonEncoder());
        $normalizer = new GetSetMethodNormalizer();
        if (!$statistic) {
            $ignoredFields = array_merge(
                $ignoredFields,
                array('lastAccessed', 'statCount')
            );
        }
        $normalizer->setIgnoredAttributes($ignoredFields);

        return new Serializer(array($normalizer), $encoders);
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