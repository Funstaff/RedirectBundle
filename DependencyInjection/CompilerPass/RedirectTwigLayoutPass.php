<?php

namespace Funstaff\Bundle\RedirectBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * RedirectTwigLayoutPass.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectTwigLayoutPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('twig');
        $layout = $container->getParameter('funstaff_redirect.layout');
        $definition->addMethodCall('addGlobal', array('funstaff_redirect_layout', $layout));
    }
}