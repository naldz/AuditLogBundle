<?php

// src/Acme/HelloBundle/DependencyInjection/Configuration.php
namespace Naldz\Bundle\AuditLogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('audit_log');
        $rootNode
            ->children()
                ->arrayNode('connection')
                    ->children()
                        ->scalarNode('host')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('username')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('password')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('database')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('user_info_provider_service')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('event_processor_service')
                    ->cannotBeEmpty()
                ->end()
                ->append($this->addEventsNode())
            ->end()
        ;
        return $treeBuilder;
    }
    
    public function addEventsNode()
    {        
        $builder = new TreeBuilder();
        $node = $builder->root('events');
        $node
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('message')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('with_details')
                        ->cannotBeEmpty()
                        ->defaultValue(false)
                    ->end()
                    ->scalarNode('event_processor_service')
                        ->cannotBeEmpty()
                    ->end()
                ->end()
            ->end()
        ;
        return $node;                   
    }
}