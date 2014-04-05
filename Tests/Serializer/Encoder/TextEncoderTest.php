<?php

namespace Funstaff\Bundle\RedirectBundle\Tests\Serializer\Encoder;

use Funstaff\Bundle\RedirectBundle\Serializer\Encoder\TextDecode;
use Funstaff\Bundle\RedirectBundle\Serializer\Encoder\TextEncode;
use Funstaff\Bundle\RedirectBundle\Serializer\Encoder\TextEncoder;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;

/**
 * TextEncoderTest.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class TextEncoderTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->encoder = new TextEncoder(
            new TextEncode(),
            new TextDecode()
        );
    }

    public function testEncode()
    {
        $data = array(
            array('source' => 'foo', 'destination' => 'bar')
        );
        $output = <<<EOF
source	destination
foo	bar
EOF;
        $result = $this->encoder->encode($data, 'text');
        $this->assertEquals($result, $output);
    }

    public function testDecode()
    {
        $data = array(
            array('source' => 'foo', 'destination' => 'bar')
        );
        $result = $this->encoder->decode($data, 'text');
        $this->assertEquals($result, $data);
    }

    public function testSupportsEncoding()
    {
        $this->assertTrue($this->encoder->supportsEncoding('text'));
        $this->assertFalse($this->encoder->supportsEncoding('test'));
    }

    public function testSupportsDecoding()
    {
        $this->assertTrue($this->encoder->supportsDecoding('text'));
        $this->assertFalse($this->encoder->supportsDecoding('test'));
    }
}