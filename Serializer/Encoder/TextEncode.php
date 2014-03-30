<?php

namespace Funstaff\Bundle\RedirectBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * TextEncode
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class TextEncode implements EncoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function encode($data, $format, array $context = array())
    {
        $output = array();
        /* Header fields */
        if (count($data) > 0) {
            $keys = array_keys($data[0]);
            array_push($output, implode("\t", $keys));
        }
        /* Data */
        foreach ($data as $line) {
            array_push($output, implode("\t", $line));
        }

        return implode("\n", $output);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEncoding($format)
    {
        return TextEncoder::FORMAT === $format;
    }
}