<?php

/*
 * This file is part of the BaitPollBundle package.
 *
 * (c) BAIT s.r.o. <http://www.bait.sk/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bait\PollBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bait_poll');

        $rootNode
            ->children()
                ->scalarNode('db_driver')->cannotBeOverwritten()->isRequired()->end()
                ->scalarNode('model_manager_name')->defaultNull()->end()

                ->arrayNode('cookie')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('prefix')->defaultValue('bait_poll_')->end()
                        ->scalarNode('duration')->defaultValue('5184000')->end()
                    ->end()
                ->end()

                ->arrayNode('form')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')->defaultValue('bait_poll.form')->end()
                        ->scalarNode('name')->defaultValue('bait_poll_form')->end()
                        ->scalarNode('factory')->defaultValue('bait_poll.form.factory.default')->end()
                        ->scalarNode('template')->defaultValue('BaitPollBundle:Poll:default.html.twig')->end()
                        ->scalarNode('theme')->defaultValue('BaitPollBundle::form_theme.html.twig')->end()
                    ->end()
                ->end()

                ->arrayNode('poll')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->end()
                        ->scalarNode('manager')->defaultValue('bait_poll.poll.manager.default')->end()
                    ->end()
                ->end()

                ->arrayNode('field')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->end()
                        ->scalarNode('manager')->defaultValue('bait_poll.field.manager.default')->end()
                    ->end()
                ->end()

                ->arrayNode('vote')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->end()
                        ->scalarNode('manager')->defaultValue('bait_poll.vote.manager.default')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
