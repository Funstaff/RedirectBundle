<?php

namespace Funstaff\Bundle\RedirectBundle\Serializer;

use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Funstaff\Bundle\RedirectBundle\Serializer\Encoder\TextEncoder;

/**
 * Serializer.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class Serializer
{
    private $serializer;

    public function __construct()
    {
        $excludeFields = array('id', 'createdAt', 'updatedAt', 'lastAccessed', 'statCount');
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setIgnoredAttributes($excludeFields);

        $this->serializer = new SymfonySerializer(
            array($normalizer),
            array(new TextEncoder())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($data, $format, array $context = array())
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data, $type, $format, array $context = array())
    {
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}