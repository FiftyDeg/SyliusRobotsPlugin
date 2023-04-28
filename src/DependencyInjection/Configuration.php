<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('fifty_deg_sylius_robots');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootChildren = $rootNode->children();

        $channels = $rootChildren->arrayNode('channels')->arrayPrototype()->children();

        $channels->scalarNode('code');
        $channels->scalarNode('robots_content');

        /** @var NodeBuilder $channels */
        $channels = $channels->end()->end();

        /** @var NodeBuilder $rootChildren */
        $rootChildren = $channels->end();

        $default = $rootChildren->arrayNode('default')->arrayPrototype()->children();
        $default->scalarNode('robots_content');

        return $treeBuilder;
    }
}
