<?php

namespace Funstaff\Bundle\RedirectBundle\Tests\Serializer\Encoder;

use Funstaff\Bundle\RedirectBundle\Serializer\Encoder\TextEncode;

/**
 * TextEncodeTest.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class TextEncodeTest extends \PHPUnit_Framework_TestCase
{
    private $encode;

    public function setup()
    {
        $this->encode = new TextEncode();
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
        $result = $this->encode->encode($data, 'text');
        $this->assertEquals($result, $output);
    }

    public function testSupportsEncoding()
    {
        $this->assertTrue($this->encode->supportsEncoding('text'));
    }
}