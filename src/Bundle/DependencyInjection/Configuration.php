<?php

namespace SymfonyWP\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symfony_wp');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('wp_installation_path')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('site_prefix')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
