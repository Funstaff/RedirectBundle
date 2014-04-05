<?php

namespace Funstaff\Bundle\RedirectBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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
        $loader->load('form.xml');
        $container->setParameter('funstaff_redirect.layout', $config['layout']);
        $container->setParameter('funstaff_redirect.enabled_stat', $config['enabled_stat']);
        $container->setParameter('funstaff_redirect.export_path', $config['export_path']);

        $this->registerListener($container, $config);
    }

    /**
     * Register Listener
     *
     * @param ContainerBuilder  $container
     * @param array             $config
     */
    private function registerListener($container, $config)
    {
        if ('exception' === $config['listener']) {
            $serviceClass = '%funstaff_redirect.redirect_exception_listener.class%';
            $tag = array('event' => 'kernel.exception','method' => 'onCoreException');
        } else {
            $serviceClass = '%funstaff_redirect.redirect_request_listener.class%';
            $tag = array('event' => 'kernel.request','method' => 'onKernelRequest');
        }

        $container->setDefinition('funstaff_redirect.redirect_listener', new Definition(
            $serviceClass
        ))
        ->addTag('kernel.event_listener', $tag)
        ->addArgument(new Reference('funstaff_redirect.redirect_manager'));
    }
}
