<?php

namespace Funstaff\Bundle\RedirectBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * TextEncoder
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class TextEncoder implements EncoderInterface, DecoderInterface
{
    const FORMAT = 'text';

    /**
     * @var JsonEncode
     */
    protected $encodingImpl;

    /**
     * @var JsonDecode
     */
    protected $decodingImpl;

    public function __construct(TextEncode $encodingImpl = null, TextDecode $decodingImpl = null)
    {
        $this->encodingImpl = null === $encodingImpl ? new TextEncode() : $encodingImpl;
        $this->decodingImpl = null === $decodingImpl ? new TextDecode() : $decodingImpl;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($data, $format, array $context = array())
    {
        return $this->encodingImpl->encode($data, self::FORMAT, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $format, array $context = array())
    {
        return $this->decodingImpl->decode($data, self::FORMAT, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEncoding($format)
    {
        return self::FORMAT === $format;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding($format)
    {
        return self::FORMAT === $format;
    }
}