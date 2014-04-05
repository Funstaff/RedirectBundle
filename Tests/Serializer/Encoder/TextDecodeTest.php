<?php

namespace Funstaff\Bundle\RedirectBundle\Tests\Serializer\Encoder;

use Funstaff\Bundle\RedirectBundle\Serializer\Encoder\TextDecode;

/**
 * TextDecodeTest.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class TextDecodeTest extends \PHPUnit_Framework_TestCase
{
    private $decode;

    public function setup()
    {
        $this->decode = new TextDecode();
    }

    public function testDecode()
    {
        $data = array('foo', 'bar');
        $result = $this->decode->decode($data, 'text');
        $this->assertEquals($result, $data);
    }

    public function testSupportsDecoding()
    {
        $this->assertTrue($this->decode->supportsDecoding('text'));
    }
}