<?php

namespace Funstaff\Bundle\RedirectBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * TextDecode
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class TextDecode implements DecoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function decode($data, $format, array $context = array())
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding($format)
    {
        return TextEncoder::FORMAT === $format;
    }
}