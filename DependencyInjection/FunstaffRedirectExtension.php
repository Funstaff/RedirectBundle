<?php

namespace Funstaff\Bundle\RedirectBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * FunstaffRedirectExtension.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class FunstaffRedirectExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $container->setParameter('funstaff_redirect.redirect_class', $config['redirect_class']);
        $container->setParameter('funstaff_redirect.enabled_stat', $config['enabled_stat']);
    }
}
