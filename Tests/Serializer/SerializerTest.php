<?php

namespace Funstaff\Bundle\RedirectBundle\Tests\Serializer\Encoder;

use Funstaff\Bundle\RedirectBundle\Serializer\Serializer;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;

/**
 * SerializerTest.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class SerializerTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->serializer = new Serializer();
    }

    public function testSerialize()
    {
        $data = array($this->getRedirect());
        $output = <<<EOF
enabled	source	destination	statusCode
1	foo	bar	302
EOF;
        $result = $this->serializer->serialize($data, 'text');
        $this->assertEquals($result, $output);
    }

    public function testDeserialize()
    {
        $data = array(
            'enabled' => 1,
            'source' => 'foo',
            'destination' => 'bar',
            'statusCode' => 302
        );
        $redirect = $this->serializer->deserialize(
            $data,
            'Funstaff\Bundle\RedirectBundle\Entity\Redirect',
            'text'
        );
        $this->assertTrue($redirect->isEnabled());
        $this->assertEquals('foo', $redirect->getSource());
        $this->assertEquals('bar', $redirect->getDestination());
        $this->assertEquals(302, $redirect->getStatusCode());
    }

    private function getRedirect()
    {
        $redirect = new Redirect();
        $redirect
            ->setSource('foo')
            ->setDestination('bar')
            ->setStatusCode(302);

        return $redirect;
    }
}