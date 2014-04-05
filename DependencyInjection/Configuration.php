<?php

namespace Funstaff\Bundle\RedirectBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('funstaff_redirect');

        $supportedlistener = array('request', 'exception');

        $rootNode
            ->children()
                ->scalarNode('listener')
                    ->validate()
                        ->ifNotInArray($supportedlistener)
                        ->thenInvalid('Listener type %s is not supported. Please choose one of '.json_encode($supportedlistener))
                    ->end()
                    ->defaultValue('exception')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('layout')
                    ->defaultValue('::base.html.twig')
                    ->cannotBeEmpty()
                ->end()
                ->booleanNode('enabled_stat')
                    ->defaultFalse()
                ->end()
                ->scalarNode('export_path')
                    ->defaultValue(sprintf(
                        '%s'.DIRECTORY_SEPARATOR.'export',
                        '%kernel.root_dir%'
                    ))
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
