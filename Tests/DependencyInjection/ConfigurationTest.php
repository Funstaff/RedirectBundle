<?php

namespace Funstaff\Bundle\RedirectBundle\Tests\DependencyInjection;

use Funstaff\Bundle\RedirectBundle\DependencyInjection\Configuration;

/**
 * ConfigurationTest.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigTreeBuilder()
    {
        $config = new Configuration();
        $this->assertInstanceOf('Symfony\Component\Config\Definition\ConfigurationInterface', $config);

        $tree = $config->getConfigTreeBuilder()->buildTree();
        $children = $tree->getChildren();
        $nodes = array('listener', 'layout', 'enabled_stat', 'export_path');
        $this->assertEquals(array_keys($children), $nodes);
    }
}