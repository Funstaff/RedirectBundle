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

        $rootNode
            ->children()
                ->scalarNode('redirect_class')
                    ->defaultValue('Funstaff\Bundle\RedirectBundle\Entity\Redirect')
                ->end()
                ->booleanNode('enabled_stat')->defaultFalse()->end()
                ->scalarNode('export_path')->defaultValue(sprintf(
                    '%s'.DIRECTORY_SEPARATOR.'export',
                    '%kernel.root_dir%'
                ))->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
