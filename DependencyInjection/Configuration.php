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

        $supportedDBDrivers = array('orm');

        $rootNode
            ->children()
                ->scalarNode('db_driver')
                    ->validate()
                        ->ifNotInArray($supportedDBDrivers)
                        ->thenInvalid('The driver %s is not supported. Please choose one of ' . json_encode($supportedDBDrivers))
                    ->end()
                    ->cannotBeEmpty()
                    ->cannotBeOverwritten()
                    ->isRequired()
                ->end()

                ->scalarNode('upload_dir')->defaultNull()->end()
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

                ->arrayNode('answer')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->end()
                        ->scalarNode('manager')->defaultValue('bait_poll.answer.manager.default')->end()
                    ->end()
                ->end()

                ->arrayNode('answer_group')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->isRequired()->end()
                        ->scalarNode('manager')->defaultValue('bait_poll.answer_group.manager.default')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
